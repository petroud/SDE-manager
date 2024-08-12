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
    $clusters = getClusters($s3);

    $cluster = [
        'uid' => uniqid(),  // Generate unique identifier
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'date_added' => date('Y-m-d H:i:s'),
        'apiserviceaddr' => $_POST['apiserviceaddr'] .':'. $_POST['apiserviceport'],
    ];

    $clusters[] = $cluster;
    saveClusters($s3, $clusters);

    echo json_encode($clusters);
}

?>