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

try {
    $stmt = $db->prepare("SELECT * FROM subjects ORDER BY name");
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $html = '';
    foreach ($subjects as $subject) {
        $html .= '<div class="list-group-item d-flex justify-content-between align-items-center">';
        $html .= $subject['name'];
        $html .= '<div class="btn-group">';
        $html .= '<button type="button" class="btn btn-sm btn-outline-primary edit-subject" data-id="' . $subject['id'] . '" data-name="' . htmlspecialchars($subject['name']) . '">Edit</button>';
        $html .= '<button type="button" class="btn btn-sm btn-outline-danger delete-subject" data-id="' . $subject['id'] . '">Delete</button>';
        $html .= '</div>';
        $html .= '</div>';
    }
    
    echo json_encode([
        'success' => true,
        'html' => $html
    ]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to get subjects']);
} 