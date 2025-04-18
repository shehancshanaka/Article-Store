<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Welcome to Article Store</h2>
                <p class="card-text">A modern system for managing and organizing your articles.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Recent Articles</h4>
                <a href="/?page=add" class="btn btn-primary">Add New Article</a>
            </div>
            <div class="card-body">
                <?php
                // Debug: Check database connection
                if (!$db) {
                    die("Database connection failed");
                }

                // Debug: Check if articles table exists
                $tableCheck = $db->query("SHOW TABLES LIKE 'articles'");
                if ($tableCheck->rowCount() == 0) {
                    die("Articles table does not exist");
                }

                // Get recent articles with their authors and pen names
                $query = "SELECT a.*, au.name as author_name, pn.name as pen_name, 
                          (SELECT image_path FROM article_images WHERE article_id = a.id AND is_thumbnail = 1 LIMIT 1) as thumbnail
                          FROM articles a
                          LEFT JOIN authors au ON a.author_id = au.id
                          LEFT JOIN pen_names pn ON a.pen_name_id = pn.id
                          ORDER BY a.date DESC
                          LIMIT 6";

                try {
                    // Debug: Print the query
                    error_log("Executing query: " . $query);
                    
                    $stmt = $db->prepare($query);
                    if ($stmt === false) {
                        throw new PDOException("Failed to prepare query: " . implode(", ", $db->errorInfo()));
                    }
                    
                    $result = $stmt->execute();
                    if ($result === false) {
                        throw new PDOException("Failed to execute query: " . implode(", ", $stmt->errorInfo()));
                    }
                    
                    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Debug: Check number of articles found
                    error_log("Number of articles found: " . count($articles));
                    
                    // Debug: Print first article if exists
                    if (!empty($articles)) {
                        error_log("First article: " . print_r($articles[0], true));
                    }
                    
                } catch (PDOException $e) {
                    error_log("Database error: " . $e->getMessage());
                    $articles = [];
                }

                // Debug: Check if we have articles
                if (empty($articles)) {
                    // Try a simpler query to see if we can get any articles
                    $simpleQuery = "SELECT * FROM articles LIMIT 1";
                    $simpleStmt = $db->query($simpleQuery);
                    if ($simpleStmt) {
                        $testArticle = $simpleStmt->fetch(PDO::FETCH_ASSOC);
                        error_log("Test article query result: " . print_r($testArticle, true));
                    }
                }
                ?>

                <?php if (empty($articles)): ?>
                    <div class="alert alert-info">
                        No articles found. Please check back later.
                        <?php if (isset($testArticle)): ?>
                            <div class="mt-2">
                                <small>Debug: Found test article with ID: <?php echo $testArticle['id'] ?? 'none'; ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($articles as $article): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <?php if (!empty($article['thumbnail'])): ?>
                                        <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" 
                                             class="card-img-top" 
                                             alt="<?php echo htmlspecialchars($article['title']); ?>"
                                             style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h5>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                By <?php echo htmlspecialchars($article['author_name']); ?>
                                                <?php if (!empty($article['pen_name'])): ?>
                                                    (<?php echo htmlspecialchars($article['pen_name']); ?>)
                                                <?php endif; ?>
                                            </small>
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <?php echo date('F j, Y', strtotime($article['date'])); ?>
                                            </small>
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="/article_store/public/?page=view&id=<?php echo $article['id']; ?>" 
                                           class="btn btn-primary">Read More</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.article-card {
    transition: transform 0.3s ease;
    cursor: pointer;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.article-image {
    transition: transform 0.3s ease;
}

.article-card:hover .article-image {
    transform: scale(1.05);
}

.article-image-placeholder {
    transition: background-color 0.3s ease;
}

.article-card:hover .article-image-placeholder {
    background-color: #e9ecef;
}
</style>

<script>
$(document).ready(function() {
    // Add hover effect for article cards
    $('.article-card').hover(
        function() {
            $(this).find('.card-img-overlay').css('opacity', '1');
        },
        function() {
            $(this).find('.card-img-overlay').css('opacity', '0');
        }
    );
});
</script> 