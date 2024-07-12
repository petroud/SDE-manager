<div class="container mt-1">
    <h2><strong>Dataset Submission</strong></h1>
        <div class="mb-4 mt-4 font-weight-normal alert alert-dark small">
        <strong>General Information about Dataset Submissions</strong> 
            <ul>
                <li>Datasets submitted here are available to whoever uses this specific instance of the SDE Manager, <strong>so be careful about privacy-oriented datasets.</strong></li>
                <li>The tools accepts only datasets in the following forms: <strong>.csv , .json, .txt</strong>. If you have an inquiry for a new format do not hesitate to <a href="mailto:dpetrou@tuc.gr">contact us</a>.</li>
                <li>On the bottom of the page you may find the already uploaded dataset files with some of their metadata. For a given dataset you may define its structure to be later used in Kafka Feeding tasks or estimation requests.</li>
                <li>The dataset name is unique. The tool will return an error if you try to upload a dataset with an already existing name.</li>
                <li>For the time being the maximum size of a dataset, for the time being, is <strong>5 GB</strong> or <strong>4.65 GiB</strong>. Trying to upload a dataset larger than this will eventually start but fail at the end. </li>
            </ul>
        </div>
    <div class="container-fluid pb-5">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="upload-tab" data-toggle="tab" href="#upload" role="tab" aria-controls="upload" aria-selected="true">Upload File</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="google-drive-tab" data-toggle="tab" href="#google-drive" role="tab" aria-controls="google-drive" aria-selected="false">Google Drive</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="url-tab" data-toggle="tab" href="#url" role="tab" aria-controls="url" aria-selected="false">Fetch from URL</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="upload-tab">
                <div class="bg-white p-3 rounded shadow-sm no-top-border">
                    <div class="dropzone d-block">
                        <label for="files" class="dropzone-container" id="dropzone">
                            <div class="file-icon"><i class="fa-solid fa-file-circle-plus text-primary"></i></div>
                            <div class="text-center pt-3 px-5">
                                <p class="w-80 h5 text-dark fw-bold">Drag your documents here to start uploading.</p>
                                <div class="hr-sect">or</div>
                                <button type="button" id="browse-files-btn" class="btn btn-primary mb-2 custom-browse-btn">Browse Files</button>
                            </div>
                        </label>
                        <input id="files" name="files[]" type="file" class="file-input" accept=".csv, .json, .txt" multiple />
                    </div>
                    <ul id="file-list" class="file-list mt-3"></ul>
                    <div class="text-center">
                        <button id="upload-button" class="btn custom-upload-btn">Upload Files</button>
                    </div>
                    <div class="progress mt-3">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="google-drive" role="tabpanel" aria-labelledby="google-drive-tab">
                <div class="bg-white p-3 rounded shadow-sm no-top-border ">
                    <h5>Fetch file from Google Drive</h5>
                    <div class="form-group">
                        <label for="file-url">Enter Google Drive Public URL:</label>
                        <input type="text" id="google-file-url" class="form-control" placeholder="Enter the file URL">
                    </div>
                    <button type="button" id="fetch-google-drive-btn" class="btn btn-primary custom-browse-btn">Fetch File from Google Drive</button>
                    <div class="progress mt-3">
                        <div id="google-progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="url" role="tabpanel" aria-labelledby="url-tab">
                <div class="bg-white p-3 rounded shadow-sm no-top-border">
                    <h5>Fetch file from URL</h5>
                    <div class="form-group">
                        <label for="file-url">Enter URL:</label>
                        <input type="text" id="url-file-url" class="form-control" placeholder="Enter the file URL">
                    </div>
                    <button type="button" id="fetch-url-btn" class="btn btn-primary custom-browse-btn">Fetch File from URL</button>
                    <div class="progress mt-3">
                        <div id="url-progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h5><strong>Available Datasets</strong></h5>

    <table class="table table-striped pb-4">
        <thead>
            <tr>
                <th>Dataset Name</th>
                <th>Size</th>
                <th>Date & Time</th>
                <th class='action-column'>Actions</th>
            </tr>
        </thead>
        <tbody id="datasets-table-body">
            <?php
            function formatSizeUnits($bytes) {
                $units = ['B', 'KB', 'MB', 'GB', 'TB'];

                $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;

                return number_format($bytes / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
            }

            $directory = __DIR__ . '/datasets/';
            $files = scandir($directory);

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $directory . $file;
                    $fileSize = formatSizeUnits(filesize($filePath));
                    $fileDateTime = date("Y-m-d H:i:s", filemtime($filePath));
                    echo "<tr>
                        <td data-label='Dataset Name'>{$file}</td>
                        <td data-label='Size'>{$fileSize}</td>
                        <td data-label='Date & Time'>{$fileDateTime}</td>
                        <td data-label='' class='action-column'>
                        <button class='btn btn-info btn-sm custom-browse-btn view-button' data-filename='{$file}'>
                            <i class='fa fa-eye' style='color: white;'></i>
                        </button>
                        <button class='btn btn-danger btn-sm custom-upload-btn delete-button' data-filename='{$file}'>
                            <i class='fa fa-trash'></i>
                        </button></td>
                    </tr>";
                    }
            }
            ?>
        </tbody>
    </table>

    <!-- Modal for viewing file content -->
    <div class="modal fade" id="viewFileModal" tabindex="-1" aria-labelledby="viewFileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewFileModalLabel">File - First 5 Lines</h5>
                </div>
                <div class="modal-body" id="file-content">
                    <!-- File content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade alert alert-danger" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Alert</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="alertModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src='js/fileHandler.js'></script>

</div>