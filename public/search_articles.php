<?php
require_once '../app/config/database.php';
require_once '../app/models/Article.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Article model
$article = new Article($db);

// Build search query
$query = "SELECT a.*, au.name as author_name, pn.name as pen_name,
          GROUP_CONCAT(DISTINCT s.name) as subjects,
          GROUP_CONCAT(DISTINCT m.title) as media,
          GROUP_CONCAT(DISTINCT t.name) as tags
          FROM articles a
          LEFT JOIN authors au ON a.author_id = au.id
          LEFT JOIN pen_names pn ON a.pen_name_id = pn.id
          LEFT JOIN article_subjects asub ON a.id = asub.article_id
          LEFT JOIN subjects s ON asub.subject_id = s.id
          LEFT JOIN article_media am ON a.id = am.article_id
          LEFT JOIN media m ON am.media_id = m.id
          LEFT JOIN article_tags at ON a.id = at.article_id
          LEFT JOIN tags t ON at.tag_id = t.id
          WHERE 1=1";

$params = [];

// Add search conditions
if (!empty($_POST['title'])) {
    $query .= " AND a.title LIKE ?";
    $params[] = '%' . $_POST['title'] . '%';
}

if (!empty($_POST['author'])) {
    $query .= " AND a.author_id = ?";
    $params[] = $_POST['author'];
}

if (!empty($_POST['subject'])) {
    $query .= " AND asub.subject_id = ?";
    $params[] = $_POST['subject'];
}

if (!empty($_POST['date_from'])) {
    $query .= " AND a.date >= ?";
    $params[] = $_POST['date_from'];
}

if (!empty($_POST['date_to'])) {
    $query .= " AND a.date <= ?";
    $params[] = $_POST['date_to'];
}

if (!empty($_POST['media'])) {
    $query .= " AND am.media_id = ?";
    $params[] = $_POST['media'];
}

// Group by article
$query .= " GROUP BY a.id ORDER BY a.date DESC";

try {
    // Prepare and execute query
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <?php if (!empty($row['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                                 class="img-fluid rounded-start" 
                                 alt="<?php echo htmlspecialchars($row['title']); ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="img-fluid rounded-start" 
                                 style="height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    Author: <?php echo htmlspecialchars($row['author_name']); ?>
                                    <?php if (!empty($row['pen_name'])): ?>
                                        (<?php echo htmlspecialchars($row['pen_name']); ?>)
                                    <?php endif; ?>
                                </small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Date: <?php echo date('F j, Y', strtotime($row['date'])); ?>
                                </small>
                            </p>
                            <a href="/article_store/public/?page=view&id=<?php echo $row['id']; ?>" class="btn btn-primary">View Article</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="alert alert-info">
            No articles found matching your search criteria. Please try different keywords.
        </div>
        <?php
    }
} catch (PDOException $e) {
    ?>
    <div class="alert alert-danger">
        Error: <?php echo htmlspecialchars($e->getMessage()); ?>
    </div>
    <?php
} 