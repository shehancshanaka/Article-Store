<?php
require_once '../app/config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['name']) || empty($_POST['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Subject name is required']);
    exit;
}

$name = trim($_POST['name']);

try {
    $stmt = $db->prepare("INSERT INTO subjects (name) VALUES (?)");
    $stmt->execute([$name]);
    
    echo json_encode([
        'success' => true,
        'id' => $db->lastInsertId(),
        'name' => $name
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add subject']);
} 