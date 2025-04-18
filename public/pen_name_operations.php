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
        if (!isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['author_id']) || empty($_POST['author_id'])) {
            echo json_encode(['error' => 'Pen name and author ID are required']);
            exit;
        }

        $name = trim($_POST['name']);
        $authorId = (int)$_POST['author_id'];

        try {
            // Check if pen name already exists for this author
            $checkStmt = $db->prepare("SELECT id FROM pen_names WHERE name = ? AND author_id = ?");
            $checkStmt->execute([$name, $authorId]);
            if ($checkStmt->rowCount() > 0) {
                echo json_encode(['error' => 'Pen name already exists for this author']);
                exit;
            }

            $stmt = $db->prepare("INSERT INTO pen_names (name, author_id) VALUES (?, ?)");
            $stmt->execute([$name, $authorId]);
            
            echo json_encode([
                'success' => true,
                'id' => $db->lastInsertId(),
                'name' => $name
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to add pen name: ' . $e->getMessage()]);
        }
        break;

    case 'update':
        if (!isset($_POST['id']) || !isset($_POST['name']) || empty($_POST['name'])) {
            echo json_encode(['error' => 'Pen name ID and new name are required']);
            exit;
        }

        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);

        try {
            $stmt = $db->prepare("UPDATE pen_names SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Pen name updated successfully'
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to update pen name: ' . $e->getMessage()]);
        }
        break;

    case 'delete':
        if (!isset($_POST['id'])) {
            echo json_encode(['error' => 'Pen name ID is required']);
            exit;
        }

        $id = (int)$_POST['id'];

        try {
            $stmt = $db->prepare("DELETE FROM pen_names WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Pen name deleted successfully'
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to delete pen name: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
} 