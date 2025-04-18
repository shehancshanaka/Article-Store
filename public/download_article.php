<?php
require_once '../app/models/Article.php';

if(isset($_GET['id'])) {
    $article = new Article($db);
    $article->id = $_GET['id'];
    
    if($article->read()) {
        // Increment download count
        $article->incrementDownloadCount();
        
        // Set headers for download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $article->title . '.txt"');
        header('Content-Length: ' . strlen($article->content));
        
        // Output the content
        echo $article->content;
        exit;
    }
}

// If article not found or ID not provided
header('Location: /article_store/public/?page=view&id=' . $_GET['id']);
exit; 