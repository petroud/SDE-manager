<?php
$targetDir = '../datasets/';

if (isset($_GET['filename'])) {
    $filename = basename($_GET['filename']);
    $filePath = $targetDir . $filename;

    if (file_exists($filePath)) {
        $lines = [];
        $file = fopen($filePath, "r");
        if ($file) {
            $count = 0;
            while (($line = fgets($file)) !== false && $count < 20) {
                $lines[] = htmlspecialchars($line);
                $count++;
            }
            fclose($file);
        }
        echo "<pre>" . implode("", $lines) . "</pre>";
    } else {
        echo "File does not exist.";
    }
} else {
    echo "No file specified.";
}