<?php
require_once '../app/config/database.php';

header('Content-Type: application/json');

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['name']) || empty($_POST['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Author name is required']);
    exit;
}

$name = trim($_POST['name']);

try {
    // Check if author already exists
    $checkStmt = $db->prepare("SELECT id FROM authors WHERE name = ?");
    $checkStmt->execute([$name]);
    if ($checkStmt->rowCount() > 0) {
        echo json_encode(['error' => 'Author already exists']);
        exit;
    }

    // Insert new author
    $stmt = $db->prepare("INSERT INTO authors (name) VALUES (?)");
    $stmt->execute([$name]);
    
    $newId = $db->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'id' => $newId,
        'name' => $name
    ]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add author: ' . $e->getMessage()]);
} 