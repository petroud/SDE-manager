<?php

include_once 'minio.php';

function getExperiments($s3) {
    try {
        $result = getObject($s3, 'experiments.json');
        $experiments = json_decode($result['Body'], true);
        if (!is_array($experiments)) {
            $experiments = [];
        }
    } catch (Exception $e) {
        $experiments = [];
    }
    return $experiments;
}

?>
    <div class="container mt-1">
        <h2><strong>Experiments</strong></h2>

        <div class="mb-4 mt-4 font-weight-normal alert alert-dark small">
            <strong>General Information about Dataset Submissions</strong> 
            <ul>
                <li>Datasets submitted here are available to whoever uses this specific instance of the SDE Manager, <strong>so be careful about privacy-oriented datasets.</strong></li>
                <li>The tools accepts only datasets in the following forms: <strong>.csv, .json, .txt</strong>. If you have an inquiry for a new format do not hesitate to <a href="mailto:dpetrou@tuc.gr">contact us</a>.</li>
                <li>On the bottom of the page you may find the already uploaded dataset files with some of their metadata. For a given dataset you may define its structure to be later used in Kafka Feeding tasks or estimation requests.</li>
                <li>The dataset name is unique. The tool will return an error if you try to upload a dataset with an already existing name.</li>
                <li>For the time being the maximum size of a dataset, for the time being, is <strong>5 GB</strong> or <strong>4.65 GiB</strong>. Trying to upload a dataset larger than this will eventually start but fail at the end.</li>
            </ul>
        </div>

        <div class="text-right mb-3">
                <button class="btn btn-primary mb-2 custom-browse-btn" data-toggle="modal" data-target="#addExperimentModal">  <i class="fa fa-plus"></i> New Experiment</button>
        </div>

        <table class="table table-striped pb-4">
            <thead>
                <tr>
                    <th>Experiment Name</th>
                    <th>Date Created</th>
                    <th>Description</th>
                    <th>SDE Cluster</th>
                    <th class='action-column'>Actions</th>
                </tr>
            </thead>
            <tbody id="experimentsTable">
                <!-- Table rows will be dynamically added here -->
            </tbody>
        </table>
    </div>

   
    <!-- Modal -->
    <div class="modal fade" id="addExperimentModal" tabindex="-1" aria-labelledby="addExperimentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExperimentModalLabel">Add New Experiment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addExperimentForm">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sde_cluster">SDE Cluster:</label>
                            <select class="form-control selectpicker" id="sde_cluster" name="sde_cluster" required>
                                <option value="">Select a cluster...</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary custom-browse-btn">Add Experiment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'clusters/get_clusters.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var select = $('#sde_cluster');
                    $.each(response, function(index, cluster) {
                        select.append('<option value="'+cluster.uid+'">'+cluster.name+' - '+cluster.apiserviceaddr+'</option>');
                    });
                    select.selectpicker('refresh'); // Refresh the selectpicker to apply the Bootstrap styles
                },
                error: function(xhr, status, error) {
                    console.error('Error loading clusters:', error);
                }
            });

            function loadExperiments() {
                $.ajax({
                    type: 'GET',
                    url: 'experiments/get_experiments.php',
                    success: function(response) {
                        var experiments = JSON.parse(response);
                        var experimentsTable = $('#experimentsTable');
                        experimentsTable.empty();
                        experiments.forEach(function(experiment) {
                            experimentsTable.append(
                                '<tr data-uid="' + experiment.uid + '">' +
                                '<td data-label="Experiment Name">' + experiment.name + '</td>' +
                                '<td data-label="Date Created">' + experiment.date_created + '</td>' +
                                '<td data-label="Description">' + experiment.description + '</td>' +
                                '<td data-label="SDE Cluster">'+'<div class="tooltip-container"> <span data-toggle="tooltip" data-placement="top" title="' + experiment.cluster_ip + '">' + experiment.cluster_name + '</span></div></td>' +
                                '<td class="action-column">' +
                                '<button class="btn btn-info btn-sm custom-browse-btn view-button">' +
                                '<i class="fa-solid fa-box-open" style="color: white;"></i>' +
                                '</button>' +
                                '<button class="btn btn-danger btn-sm custom-upload-btn delete-button" data-uid="' + experiment.uid + '">' +
                                '<i class="fa fa-trash"></i>' +
                                '</button>' +
                                '</td>' +
                                '</tr>'
                            );
                        });
                    }
                });
            }

            loadExperiments();

            // Handle add experiment form submission
            $('#addExperimentForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'experiments/add_experiment.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        var experiments = JSON.parse(response);
                        var experimentsTable = $('#experimentsTable');
                        experimentsTable.empty();
                        experiments.forEach(function(experiment) {
                            experimentsTable.append(
                                '<tr data-uid="' + experiment.uid + '">' +
                                '<td data-label="Experiment Name">' + experiment.name + '</td>' +
                                '<td data-label="Date Created">' + experiment.date_created + '</td>' +
                                '<td data-label="Description">' + experiment.description + '</td>' +
                                '<td data-label="SDE Cluster">' + experiment.sde_cluster + '</td>' +
                                '<td class="action-column">' +
                                '<button class="btn btn-info btn-sm custom-browse-btn view-button">' +
                                '<i class="fa fa-eye" style="color: white;"></i>' +
                                '</button>' +
                                '<button class="btn btn-danger btn-sm custom-upload-btn delete-button" data-uid="' + experiment.uid + '">' +
                                '<i class="fa fa-trash"></i>' +
                                '</button>' +
                                '</td>' +
                                '</tr>'
                            );
                        });
                        $('#addExperimentForm')[0].reset();
                        $('#addExperimentModal').modal('hide');
                        $('#addExperimentModal').modal('hide');
                        showSuccessBar('Experiment added! Click on view to set up the workflow')

                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete-button', function() {
                var experimentUID = $(this).data('uid');
                if (confirm('Are you sure you want to delete this experiment?')) {
                    $.ajax({
                        type: 'POST',
                        url: 'experiments/delete_experiment.php',
                        data: { uid: experimentUID },
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.success) {
                                // Remove the row from the table
                                $('tr[data-uid="' + experimentUID + '"]').remove();
                                showSuccessBar('Experiment deleted!');

                            } else {
                                showErrorBar('Failed to delete the experiment');
                            }
                        }
                    });
                }
            });
        });
    </script>