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

if (!isset($_POST['author_id']) || empty($_POST['author_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Author ID is required']);
    exit;
}

$authorId = (int)$_POST['author_id'];

try {
    $stmt = $db->prepare("SELECT * FROM pen_names WHERE author_id = ? ORDER BY name");
    $stmt->execute([$authorId]);
    $penNames = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $html = '';
    foreach ($penNames as $penName) {
        $html .= '<div class="list-group-item d-flex justify-content-between align-items-center">';
        $html .= $penName['name'];
        $html .= '<div class="btn-group">';
        $html .= '<button type="button" class="btn btn-sm btn-outline-primary edit-pen-name" data-id="' . $penName['id'] . '" data-name="' . htmlspecialchars($penName['name']) . '">Edit</button>';
        $html .= '<button type="button" class="btn btn-sm btn-outline-danger delete-pen-name" data-id="' . $penName['id'] . '">Delete</button>';
        $html .= '</div>';
        $html .= '</div>';
    }
    
    echo json_encode([
        'success' => true,
        'html' => $html
    ]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to get pen names']);
} 