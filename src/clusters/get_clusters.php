<?php

include_once '../minio.php'; // Adjust the path if necessary

function getClusters($s3) {
    try {
        $result = getObject($s3, 'clusters.json');
        $clusters = json_decode($result['Body'], true);
        if (!is_array($clusters)) {
            $clusters = [];
        }
    } catch (Exception $e) {
        $clusters = [];
    }
    return $clusters;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $clusters = getClusters($s3);
    echo json_encode($clusters);
}

?>