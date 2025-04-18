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
        if (!isset($_POST['name']) || empty($_POST['name'])) {
            echo json_encode(['error' => 'Subject name is required']);
            exit;
        }

        $name = trim($_POST['name']);

        try {
            // Check if subject already exists
            $checkStmt = $db->prepare("SELECT id FROM subjects WHERE name = ?");
            $checkStmt->execute([$name]);
            if ($checkStmt->rowCount() > 0) {
                echo json_encode(['error' => 'Subject already exists']);
                exit;
            }

            $stmt = $db->prepare("INSERT INTO subjects (name) VALUES (?)");
            $stmt->execute([$name]);
            
            echo json_encode([
                'success' => true,
                'id' => $db->lastInsertId(),
                'name' => $name
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to add subject: ' . $e->getMessage()]);
        }
        break;

    case 'update':
        if (!isset($_POST['id']) || !isset($_POST['name']) || empty($_POST['name'])) {
            echo json_encode(['error' => 'Subject ID and new name are required']);
            exit;
        }

        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);

        try {
            $stmt = $db->prepare("UPDATE subjects SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Subject updated successfully'
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to update subject: ' . $e->getMessage()]);
        }
        break;

    case 'delete':
        if (!isset($_POST['id'])) {
            echo json_encode(['error' => 'Subject ID is required']);
            exit;
        }

        $id = (int)$_POST['id'];

        try {
            $stmt = $db->prepare("DELETE FROM subjects WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Subject deleted successfully'
            ]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to delete subject: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
} 