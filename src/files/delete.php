<?php
$targetDir = '../datasets/';
$response = ['status' => 'error'];

if (isset($_POST['filename'])) {
    $filename = basename($_POST['filename']);
    $filePath = $targetDir . $filename;

    if (file_exists($filePath)) {
        unlink($filePath);
        $response['status'] = 'success';
    }
}

echo json_encode($response);