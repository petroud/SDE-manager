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
    $uid = isset($_GET['uid']) ? $_GET['uid'] : null;

    $clusters = getClusters($s3);
   
    if ($uid) {
        // Filter clusters based on the uid
        $filteredClusters = array_filter($clusters, function($cluster) use ($uid) {
            return isset($cluster['uid']) && $cluster['uid'] == $uid;
        });

        echo json_encode(array_values($filteredClusters));
    } else {
        // If no UID is provided, return all clusters
        echo json_encode($clusters);
    }
}

?>