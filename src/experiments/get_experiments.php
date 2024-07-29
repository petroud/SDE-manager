<?php

include_once '../minio.php'; // Adjust the path if necessary

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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $experiments = getExperiments($s3);
    echo json_encode($experiments);
}

?>