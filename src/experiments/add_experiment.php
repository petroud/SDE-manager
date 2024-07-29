<?php
include_once '../minio.php';

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

function saveExperiments($s3, $experiments) {
    putObject($s3, 'experiments.json', json_encode($experiments, JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $experiments = getExperiments($s3);

    $experiment = [
        'uid' => uniqid(),  // Generate unique identifier
        'name' => $_POST['name'],
        'date_created' => date('Y-m-d H:i:s'),
        'description' => $_POST['description'],
        'sde_cluster' => $_POST['sde_cluster']
    ];

    $experiments[] = $experiment;
    saveExperiments($s3, $experiments);

    echo json_encode($experiments);
}

?>