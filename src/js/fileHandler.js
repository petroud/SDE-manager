function showModal(message) {
    // Set the message in the modal body
    $('#alertModalBody').html(message);
    // Show the modal
    $('#alertModal').modal('show');

    // Automatically close the modal after 5 seconds
    setTimeout(function() {
        $('#alertModal').modal('hide');
    }, 5000);
}

function isApprovedFile(fileName) {
    const fileExtension = fileName.split('.').pop().toLowerCase();

    // Define supported file types
    const fileTypes = {
        csv: "CSV",
        json: "JSON",
        txt: "TXT"
    };

    if (fileTypes[fileExtension]) {
        return true;
    } else {
        return false;
    }
}

function isFormDataValid(formData) {
    for (let entry of formData.entries()) {
        const [key, value] = entry;
        if (value instanceof File) {
            if(!isApprovedFile(value.name)){
                return false;
            }
        }
    }
    return true;
}



$(document).ready(function() {
    var dropzone = $('#dropzone');
    var fileList = $('#file-list');

    function updateFileList(files) {
        $.each(files, function(i, file) {
            var listItem = $('<li>').addClass('file-item');
            listItem.html(`<div class="file-icon"><i class="fa-solid fa-file"  style="color: #3C5A80;"></i></div>
                           <span class="file-name">${file.name}</span>`);
            fileList.append(listItem);
        });
    }


    $('#browse-files-btn').on('click', function() {
        $('#files').click();
    });

    // Handle file input change
    $('#files').on('change', function() {
        updateFileList(this.files);
    });

    // Drag-and-drop event handlers
    dropzone.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.addClass('dragover');
    });

    dropzone.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.removeClass('dragover');
    });

    dropzone.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.removeClass('dragover');
        var files = e.originalEvent.dataTransfer.files;
        $('#files')[0].files = files;
        updateFileList(files);
    });


    // Upload files on button click
    $('#upload-button').on('click', function() {
        var formData = new FormData();
        $.each($('#files')[0].files, function(i, file) {
            formData.append('files[]', file);
        });

        if (!isFormDataValid(formData)) {
            document.getElementById('files').value = '';
            showModal('Dataset Files should be in .csv .json or .txt form.');
            $('#file-list').html('');
            $('#progress-bar').width('0%'); $('#progress-bar').html('0%');
            return;
        }

        $.ajax({
            url: 'files/upload.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        $('#progress-bar').width(percentComplete + '%');
                        $('#progress-bar').html(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                document.getElementById('files').value = '';
                $('#file-list').html('');
                $('#progress-bar').width('0%'); $('#progress-bar').html('0%'); 
                var files = JSON.parse(response); 
                files.forEach(function(file) { 
                    $('#datasets-table-body').append( 
                        `<tr><td data-label='Dataset Name'>${file.filename}</td> 
                             <td data-label='Size'>${file.size}</td> 
                             <td data-label='Date & Time'>${file.datetime}</td>  
                             <td data-label='' class='action-column'>
                                <button class='btn btn-info btn-sm custom-browse-btn view-button' data-filename='${file.filename}'>
                                    <i class='fa fa-eye' style='color: white;'></i>
                                </button>
                                <button class='btn btn-danger btn-sm custom-upload-btn delete-button' data-filename='${file.filename}'>
                                    <i class='fa fa-trash'></i>
                                </button>
                            </td>` ); 
                }); 
            },
            error: function(xhr, status, error) {
                document.getElementById('files').value = '';
                $('#file-list').html('');
                $('#progress-bar').width('0%'); $('#progress-bar').html('0%'); 
                var response = xhr.responseText;
                try {
                    response = JSON.parse(xhr.responseText);
                } catch (e) {
                    showModal("An error occurred: " + error);
                    return;
                }

                if (xhr.status === 400) {
                    showModal(response.error); // Specific error messages from PHP script
                } else if (xhr.status === 500) {
                    showModal(response.error); // General server error
                } else {
                    showModal("An unexpected error occurred: " + error);
                }
            }
        });
    });
    
    $(document).on('click', '.delete-button', function() {
        var filename = $(this).data('filename');
        var row = $(this).closest('tr');
        $.ajax({
            url: 'files/delete.php',
            type: 'POST',
            data: { filename: filename },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    row.remove();
                }
            }
        });
    });

    $(document).on('click', '.view-button', function() {
        var filename = $(this).data('filename');
        $.ajax({
            url: 'files/view.php',
            type: 'GET',
            data: { filename: filename },
            success: function(response) {
                $('#file-content').html(response);
                $('#viewFileModal').modal('show');
            }
        });
    });

    $(document).on('click', '.structure-button', function() {
        var filename = $(this).data('filename');
        $.ajax({
            url: 'files/getDatasetStructure.php',
            type: 'GET',
            data: { filename: filename },
            success: function(response) {
                $('#structureModalLabel').html("Dataset Structure: <strong>"+filename+"</strong>");
                $('#dataset-structure').html(response);
                $('#structureModal').modal('show');
            }
        });
    });

    $('#fetch-google-drive-btn').on('click', function(event) {
        event.preventDefault();
        var fileURL = $('#google-file-url').val();
        $('#google-progress-bar').css('width', '0%').attr('aria-valuenow', 0).text('0%');

        $.ajax({
            url: 'files/getGoogleFile.php',
            type: 'POST',
            data: { fileURL: fileURL },
            xhr: function() {
                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(event) {
                    if (event.lengthComputable) {
                        var percentComplete = Math.round((event.loaded / event.total) * 100);
                        $('#google-progress-bar').css('width', percentComplete + '%').attr('aria-valuenow', percentComplete).text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'progress') {
                    $('#google-progress-bar').css('width', jsonResponse.progress + '%').attr('aria-valuenow', jsonResponse.progress).text(jsonResponse.progress + '%');
                } else if (jsonResponse.status === 'success') {

                } else if (jsonResponse.status === 'error') {
                    showModal(jsonResponse.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showModal('Error: ' + textStatus);
            }
        });
    });

    $('#fetch-url-btn').on('click', function(event) {
        event.preventDefault();
        var fileURL = $('#url-file-url').val();
        $('#url-progress-bar').css('width', '0%').attr('aria-valuenow', 0).text('0%');

        $.ajax({
            url: 'files/getURLFile.php',
            type: 'POST',
            data: { fileURL: fileURL },
            xhr: function() {
                var xhr = new XMLHttpRequest();
                xhr.addEventListener('progress', function(event) {
                    if (event.lengthComputable) {
                        var percentComplete = Math.round((event.loaded / event.total) * 100);
                        $('#url-progress-bar').css('width', percentComplete + '%').attr('aria-valuenow', percentComplete).text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'progress') {
                    $('#url-progress-bar').css('width', jsonResponse.progress + '%').attr('aria-valuenow', jsonResponse.progress).text(jsonResponse.progress + '%');
                } else if (jsonResponse.status === 'success') {
                    $('#url-progress-bar').css('width', '0%').attr('aria-valuenow', 0).text('0%');

                } else if (jsonResponse.status === 'error') {
                    showModal(jsonResponse.message);
                    $('#url-progress-bar').css('width', '0%').attr('aria-valuenow', 0).text('0%');

                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showModal('Error: ' + textStatus);
            }
        });
    });
});
