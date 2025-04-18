<?php
require_once '../app/config/database.php';
require_once '../app/models/Article.php';

session_start();

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Article model
$article = new Article($db);

// Process form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        $db->beginTransaction();

        // Handle new authors
        if (!empty($_POST['new_authors'])) {
            foreach ($_POST['new_authors'] as $authorName) {
                if (!empty($authorName)) {
                    $stmt = $db->prepare("INSERT INTO authors (name) VALUES (?)");
                    $stmt->execute([trim($authorName)]);
                }
            }
        }

        // Handle new subjects
        if (!empty($_POST['new_subjects'])) {
            foreach ($_POST['new_subjects'] as $subjectName) {
                if (!empty($subjectName)) {
                    $stmt = $db->prepare("INSERT INTO subjects (name) VALUES (?)");
                    $stmt->execute([trim($subjectName)]);
                }
            }
        }

        // Handle new media
        if (!empty($_POST['new_media'])) {
            foreach ($_POST['new_media'] as $media) {
                if (!empty($media['title']) && !empty($media['type'])) {
                    $stmt = $db->prepare("INSERT INTO media (title, type) VALUES (?, ?)");
                    $stmt->execute([trim($media['title']), $media['type']]);
                }
            }
        }

        // Set article properties
        $article->title = $_POST['title'];
        $article->date = $_POST['date'];
        $article->author_id = $_POST['author'];
        $article->pen_name_id = !empty($_POST['pen_name']) ? $_POST['pen_name'] : null;
        $article->page_number = $_POST['page_number'];
        $article->image_path = $_POST['image_path'];

        // Create article
        if ($article->create()) {
            $articleId = $db->lastInsertId();

            // Handle subjects
            if (!empty($_POST['subjects'])) {
                $stmt = $db->prepare("INSERT INTO article_subjects (article_id, subject_id) VALUES (?, ?)");
                foreach ($_POST['subjects'] as $subjectId) {
                    $stmt->execute([$articleId, $subjectId]);
                }
            }

            // Handle media
            if (!empty($_POST['related_media'])) {
                $stmt = $db->prepare("INSERT INTO article_media (article_id, media_id) VALUES (?, ?)");
                foreach ($_POST['related_media'] as $mediaId) {
                    $stmt->execute([$articleId, $mediaId]);
                }
            }

            // Handle tags
            if (!empty($_POST['tags'])) {
                $tags = array_map('trim', explode(',', $_POST['tags']));
                $tagStmt = $db->prepare("INSERT INTO tags (name) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
                $articleTagStmt = $db->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
                
                foreach ($tags as $tagName) {
                    if (!empty($tagName)) {
                        $tagStmt->execute([$tagName]);
                        $tagId = $db->lastInsertId();
                        $articleTagStmt->execute([$articleId, $tagId]);
                    }
                }
            }

            // Commit transaction
            $db->commit();

            // Set success message
            $_SESSION['message'] = 'Article saved successfully!';
            $_SESSION['message_type'] = 'success';
            
            // Redirect to home page
            header('Location: /?page=home');
            exit;
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $db->rollBack();
        
        // Set error message
        $_SESSION['message'] = 'Error saving article: ' . $e->getMessage();
        $_SESSION['message_type'] = 'danger';
        
        // Redirect back to form
        header('Location: /?page=add');
        exit;
    }
} else {
    // Redirect if not POST request
    header('Location: /?page=add');
    exit;
} 