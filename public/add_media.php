<?php
require_once '../app/config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['type']) || empty($_POST['type'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Media title and type are required']);
    exit;
}

$title = trim($_POST['title']);
$type = trim($_POST['type']);

try {
    $stmt = $db->prepare("INSERT INTO media (title, type) VALUES (?, ?)");
    $stmt->execute([$title, $type]);
    
    echo json_encode([
        'success' => true,
        'id' => $db->lastInsertId(),
        'title' => $title,
        'type' => $type
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add media']);
} 