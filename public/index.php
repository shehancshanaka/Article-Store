<?php
session_start();

require_once '../app/config/database.php';
require_once '../app/models/Article.php';

$database = new Database();
$db = $database->getConnection();

$article = new Article($db);

// Get the current page from the URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Include the header
include '../app/views/layouts/header.php';

// Route to the appropriate page
switch($page) {
    case 'home':
        include '../app/views/home.php';
        break;
    case 'add':
        include '../app/views/add_article.php';
        break;
    case 'search':
        include '../app/views/search.php';
        break;
    case 'view':
        include '../app/views/view.php';
        break;
    case 'edit':
        include '../app/views/edit.php';
        break;
    default:
        include '../app/views/home.php';
}

// Include the footer
include '../app/views/layouts/footer.php'; 