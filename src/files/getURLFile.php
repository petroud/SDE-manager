<?php

// Set headers to allow cross-origin requests (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the file URL from the POST request
    $fileUrl = $_POST['fileURL'];

    // Function to get file name from URL
    function getFileNameFromUrl($url) {
        $path = parse_url($url, PHP_URL_PATH);
        return basename($path);
    }

    // Get the file name from the URL
    $fileName = getFileNameFromUrl($fileUrl);
    
    if ($fileName === null) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid URL']);
        exit;
    }

    // Destination directory on the server
    $saveDir = '../datasets/'; // Ensure this directory exists and is writable

    // Full path to save the file (use file name from URL)
    $savePath = $saveDir . $fileName;

    // Initialize cURL session
    $ch = curl_init($fileUrl);

    // Open file handle for writing
    $fp = fopen($savePath, 'wb');
    if (!$fp) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to open file for writing']);
        exit;
    }

    // Set cURL options
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($resource, $download_size, $downloaded, $upload_size, $uploaded) {
        static $prevProgress = 0;
        if ($download_size > 0) {
            $progress = round(($downloaded / $download_size) * 100);
            if ($progress > $prevProgress) {
                $prevProgress = $progress;
                echo json_encode(['status' => 'progress', 'progress' => $progress]);
                ob_flush();
                flush();
            }
        }
    });
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects

    // Execute cURL session
    curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo json_encode(['status' => 'error', 'message' => curl_error($ch)]);
    } else {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
            echo json_encode(['status' => 'success', 'message' => 'File downloaded successfully', 'path' => $savePath]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to download file, HTTP status code: ' . $http_code]);
        }
    }

    // Close cURL session and file handle
    curl_close($ch);
    fclose($fp);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
