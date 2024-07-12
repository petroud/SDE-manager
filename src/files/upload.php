<?php
$targetDir = '../datasets/';
$response = [];

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

if (!empty($_FILES['files']['name'][0])) {
    $totalFiles = count($_FILES['files']['name']);

    if ($totalFiles > 10) {
        http_response_code(400);
        $response = ['error' => 'You can only upload up to 10 files.'];
        echo json_encode($response);
        exit;
    }

    foreach ($_FILES['files']['name'] as $key => $filename) {
        $targetFilePath = $targetDir . basename($filename);
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        if (file_exists($targetFilePath)) {
            http_response_code(400); 
            $response = ['error' => 'File <strong> "' . $filename . '"</strong> already exists'];
            echo json_encode($response);
            exit;
        }

        if (in_array($fileType, ['csv', 'txt', 'json'])) {
            if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $targetFilePath)) {
                $response[] = [
                    'filename' => $filename,
                    'size' => filesize($targetFilePath),
                    'datetime' => date("Y-m-d H:i:s", filemtime($targetFilePath))
                ];
            } else {
                http_response_code(400);
                $response = ['error' => 'File <strong> "' . $filename . '"</strong> upload failed due to size restrictions'];
                echo json_encode($response);
                exit;
            }
        } else {
            http_response_code(400);
            $response = ['error' => 'File <strong> "' . $filename . '"</strong> upload failed due to invalid format'];
            echo json_encode($response);
            exit;
        }
    }
}

echo json_encode($response);