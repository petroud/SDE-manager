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
    $uidToDelete = $_POST['uid'];

    $experiments = getExperiments($s3);
    $experiments = array_filter($experiments, function($experiment) use ($uidToDelete) {
        return $experiment['uid'] !== $uidToDelete;
    });
    $experiments = array_values($experiments); // Reindex array

    saveExperiments($s3, $experiments);

    // Return a success response
    echo json_encode(['success' => true]);
}

?>