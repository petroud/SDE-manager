<?php

include_once '../minio.php';

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

function saveClusters($s3, $clusters) {
    putObject($s3, 'clusters.json', json_encode($clusters, JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uidToDelete = $_POST['uid'];

    $clusters = getClusters($s3);
    $clusters = array_filter($clusters, function($cluster) use ($uidToDelete) {
        return $cluster['uid'] !== $uidToDelete;
    });
    $clusters = array_values($clusters); // Reindex array

    saveClusters($s3, $clusters);

    // Return a success response
    echo json_encode(['success' => true]);
}

?>