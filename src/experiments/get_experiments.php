<?php

include_once '../minio.php'; // Adjust the path if necessary

// Function to get clusters
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

// Function to get experiments
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

// Function to get cluster info by UID
function getClusterByUid($clusters, $uid) {
    foreach ($clusters as $cluster) {
        if ($cluster['uid'] === $uid) {
            return [
                'name' => $cluster['name'],
                'ip' => $cluster['apiserviceaddr']
            ];
        }
    }
    return null;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $experiments = getExperiments($s3);
    $clusters = getClusters($s3);
    
    // Attach cluster info to each experiment
    foreach ($experiments as &$experiment) {
        if (isset($experiment['sde_cluster'])) {
            $clusterInfo = getClusterByUid($clusters, $experiment['sde_cluster']);
            if ($clusterInfo) {
                $experiment['cluster_name'] = $clusterInfo['name'];
                $experiment['cluster_ip'] = $clusterInfo['ip'];
            } else {
                $experiment['cluster_name'] = null;
                $experiment['cluster_ip'] = null;
            }
        } else {
            $experiment['cluster_name'] = null;
            $experiment['cluster_ip'] = null;
        }
    }

    echo json_encode($experiments);
}
?>