<?php
if (!isset($_GET['id'])) {
    header('Location: /article_store/public/');
    exit;
}

$id = (int)$_GET['id'];
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
          WHERE a.id = ?
          GROUP BY a.id";

try {
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        header('Location: /article_store/public/');
        exit;
    }
} catch (PDOException $e) {
    header('Location: /article_store/public/');
    exit;
}
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="position-relative">
                <?php if (!empty($article['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($article['image_path']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($article['title']); ?>"
                         style="height: 400px; object-fit: cover;">
                <?php else: ?>
                    <div class="card-img-top" 
                         style="height: 400px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image fa-5x text-muted"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-body">
                <h2 class="card-title mb-4"><?php echo htmlspecialchars($article['title']); ?></h2>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Author</h5>
                        <p><?php echo htmlspecialchars($article['author_name']); ?>
                        <?php if (!empty($article['pen_name'])): ?>
                            <br><small class="text-muted">Pen Name: <?php echo htmlspecialchars($article['pen_name']); ?></small>
                        <?php endif; ?></p>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Date</h5>
                        <p><?php echo date('F j, Y', strtotime($article['date'])); ?></p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Page Number</h5>
                        <p><?php echo $article['page_number']; ?></p>
                    </div>
                    
                    <?php if (!empty($article['subjects'])): ?>
                    <div class="col-md-6">
                        <h5>Subjects</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach (explode(',', $article['subjects']) as $subject): ?>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($subject); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($article['media'])): ?>
                <div class="mb-4">
                    <h5>Related Media</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach (explode(',', $article['media']) as $media): ?>
                            <span class="badge bg-info"><?php echo htmlspecialchars($media); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($article['tags'])): ?>
                <div class="mb-4">
                    <h5>Tags</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach (explode(',', $article['tags']) as $tag): ?>
                            <span class="badge bg-primary"><?php echo htmlspecialchars($tag); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="/article_store/public/?page=edit&id=<?php echo $article['id']; ?>" class="btn btn-primary">Edit Article</a>
                    <a href="/article_store/public/?page=home" class="btn btn-secondary">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div> 