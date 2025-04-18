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

$action = $_POST['action'] ?? '';

switch($action) {
    case 'add':
        if (!isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['type']) || empty($_POST['type'])) {
            echo json_encode(['error' => 'Media title and type are required']);
            exit;
        }

        $title = trim($_POST['title']);
        $type = trim($_POST['type']);

        try {
            // Check if media already exists
            $checkStmt = $db->prepare("SELECT id FROM media WHERE title = ? AND type = ?");
            $checkStmt->execute([$title, $type]);
            if ($checkStmt->rowCount() > 0) {
                echo json_encode(['error' => 'Media already exists']);
                exit;
            }

            $stmt = $db->prepare("INSERT INTO media (title, type) VALUES (?, ?)");
            $stmt->execute([$title, $type]);
            
            echo json_encode([
                'success' => true,
                'id' => $db->lastInsertId(),
                'title' => $title,
                'type' => $type
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to add media: ' . $e->getMessage()]);
        }
        break;

    case 'update':
        if (!isset($_POST['id']) || !isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['type']) || empty($_POST['type'])) {
            echo json_encode(['error' => 'Media ID, title, and type are required']);
            exit;
        }

        $id = (int)$_POST['id'];
        $title = trim($_POST['title']);
        $type = trim($_POST['type']);

        try {
            $stmt = $db->prepare("UPDATE media SET title = ?, type = ? WHERE id = ?");
            $stmt->execute([$title, $type, $id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Media updated successfully'
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to update media: ' . $e->getMessage()]);
        }
        break;

    case 'delete':
        if (!isset($_POST['id'])) {
            echo json_encode(['error' => 'Media ID is required']);
            exit;
        }

        $id = (int)$_POST['id'];

        try {
            $stmt = $db->prepare("DELETE FROM media WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Media deleted successfully'
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to delete media: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
} 