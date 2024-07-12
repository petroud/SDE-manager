<?php

// Set headers to allow cross-origin requests (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the Google Drive link from the POST request
    $googleDriveLink = $_POST['fileURL'];

    // Function to extract file ID from Google Drive link
    function extractFileId($googleDriveLink) {
        $pattern = '/\/d\/(.*?)\//';
        preg_match($pattern, $googleDriveLink, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        } else {
            // Handle the alternative link structure
            $pattern = '/id=([^&]*)/';
            preg_match($pattern, $googleDriveLink, $matches);
            return $matches[1] ?? null;
        }
    }

    // Extract the file ID
    $fileId = extractFileId($googleDriveLink);

    if ($fileId === null) {
        die(json_encode(['status' => 'error', 'message' => 'Invalid Google Drive link']));
    }

    // Google Drive direct download URL
    $fileUrl = 'https://drive.google.com/uc?export=download&id=' . $fileId;

    // Destination directory on the server
    $saveDir = '../datasets/';

    // Full path to save the file (file ID as name)
    $savePath = $saveDir . $fileId;

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