<?php
require_once '../app/config/database.php';
require_once '../vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;

// Set headers for JSON response
header('Content-Type: application/json');

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file = $_FILES['file'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.']);
    exit;
}

// Validate file size (max 10MB)
$maxSize = 10 * 1024 * 1024; // 10MB in bytes
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['error' => 'File size exceeds 10MB limit']);
    exit;
}

// Create upload directory if it doesn't exist
$uploadDir = 'uploads/articles/' . date('Y/m');
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '.' . $extension;
$filepath = $uploadDir . '/' . $filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // Create thumbnail
    $thumbnailPath = $uploadDir . '/thumb_' . $filename;
    Image::make($filepath)
        ->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })
        ->save($thumbnailPath);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'filepath' => '/' . $filepath,
        'thumbnail' => '/' . $thumbnailPath
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to upload file']);
} 