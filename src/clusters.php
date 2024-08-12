    <div class="container mt-1">
        <h2><strong>SDE Cluster Instances</strong></h2>

        <div class="mb-4 mt-4 font-weight-normal alert alert-dark small">
            <strong>General Information about Clusters</strong> 
            <ul>
                <li>In this page you may define SDE instances in order to use them for experimentation in the appropriate page.</li>
                <li>You can define an SDE instance by providing the <strong>address of the API service</strong> which acts as input/output source to it.</li>
                <li>Each instance defined here may be used as an experimentation platform in the 'Experiments' page.</li>

            </ul>
        </div>

        <div class="text-right mb-3">
                <button class="btn btn-primary mb-2 custom-browse-btn" data-toggle="modal" data-target="#addClusterModal">  <i class="fa fa-plus"></i> New Cluster</button>
        </div>

        <table class="table table-striped pb-4">
            <thead>
                <tr>
                    <th>Cluster Name</th>
                    <th>API Service Address</th>
                    <th>Description</th>
                    <th>Date Added</th>
                    <th class='action-column'>Actions</th>
                </tr>
            </thead>
            <tbody id="clustersTable">
                <!-- Table rows will be dynamically added here -->
            </tbody>
        </table>
    </div>

   
    <!-- Modal -->
    <div class="modal fade" id="addClusterModal" tabindex="-1" aria-labelledby="addClusterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClusterModalLabel">Add New Cluster</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addClusterForm">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="apiserviceaddr">API Service Address & Port:</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="apiserviceaddr" name="apiserviceaddr" placeholder="Address (e.g., 192.168.1.1 or example.com)" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="apiserviceport" name="apiserviceport" placeholder="Port (e.g., 8080)" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary custom-browse-btn">Add Cluster</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function loadClusters() {
                $.ajax({
                    type: 'GET',
                    url: 'clusters/get_clusters.php',
                    success: function(response) {
                        var clusters = JSON.parse(response);
                        var clustersTable = $('#clustersTable');
                        clustersTable.empty();
                        clusters.forEach(function(cluster) {
                            clustersTable.append(
                                '<tr data-uid="' + cluster.uid + '">' +
                                '<td data-label="Cluster Name">' + cluster.name + '</td>' +
                                '<td data-label="API Service Address">' + cluster.apiserviceaddr + '</td>' +
                                '<td data-label="Description"> <div class="description"  title="'+cluster.description+'">' + cluster.description + '</div></td>' +
                                '<td data-label="Date Created">' + cluster.date_added + '</td>' +
                                '<td class="action-column">' +
                                '<button class="btn btn-info btn-sm custom-browse-btn view-button">' +
                                '<i class="fa fa-pen" style="color: white;"></i>' +
                                '</button>' +
                                '<button class="btn btn-danger btn-sm custom-upload-btn delete-button" data-uid="' + cluster.uid + '">' +
                                '<i class="fa fa-trash"></i>' +
                                '</button>' +
                                '</td>' +
                                '</tr>'
                            );
                        });
                    }
                });
            }

            loadClusters();

            // Handle add cluster form submission
            $('#addClusterForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'clusters/add_cluster.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        var clusters = JSON.parse(response);
                        var clustersTable = $('#clustersTable');
                        clustersTable.empty();
                        clusters.forEach(function(cluster) {
                            clustersTable.append(
                                '<tr data-uid="' + cluster.uid + '">' +
                                '<td data-label="Cluster Name">' + cluster.name + '</td>' +
                                '<td data-label="API Service Address">' + cluster.apiserviceaddr + '</td>' +
                                '<td data-label="Description"> <div class="description"  title="'+cluster.description+'">' + cluster.description + '</div></td>' +
                                '<td data-label="Date Added">' + cluster.date_added + '</td>' +
                                '<td class="action-column">' +
                                '<button class="btn btn-info btn-sm custom-browse-btn view-button">' +
                                '<i class="fa fa-pen" style="color: white;"></i>' +
                                '</button>' +
                                '<button class="btn btn-danger btn-sm custom-upload-btn delete-button" data-uid="' + cluster.uid + '">' +
                                '<i class="fa fa-trash"></i>' +
                                '</button>' +
                                '</td>' +
                                '</tr>'
                            );
                        });
                        $('#addClusterForm')[0].reset();
                        $('#addClusterModal').modal('hide');
                        $('#addClusterModal').modal('hide');
                        showSuccessBar('Cluster added! Click on view to set up the workflow')

                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete-button', function() {
                var clusterUID = $(this).data('uid');
                if (confirm('Are you sure you want to delete this cluster?')) {
                    $.ajax({
                        type: 'POST',
                        url: 'clusters/delete_cluster.php',
                        data: { uid: clusterUID },
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.success) {
                                // Remove the row from the table
                                $('tr[data-uid="' + clusterUID + '"]').remove();
                                showSuccessBar('Cluster deleted!');

                            } else {
                                showErrorBar('Failed to delete the cluster');
                            }
                        }
                    });
                }
            });
        });
    </script>