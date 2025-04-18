<?php
require_once '../app/models/Article.php';

$article = new Article($db);
$article->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Article ID not found.');

if($article->read()) {
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h1><?php echo htmlspecialchars($article->title); ?></h1>
            <p class="text-muted">
                By <?php echo htmlspecialchars($article->author_name); ?>
                <?php if($article->pen_name): ?>
                    (<?php echo htmlspecialchars($article->pen_name); ?>)
                <?php endif; ?>
                | <?php echo date('F j, Y', strtotime($article->date)); ?>
            </p>
            
            <!-- Image Gallery -->
            <?php if(!empty($article->images)): ?>
            <div class="article-gallery mb-4">
                <div id="articleCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php foreach($article->images as $index => $image): ?>
                            <button type="button" data-bs-target="#articleCarousel" 
                                    data-bs-slide-to="<?php echo $index; ?>" 
                                    <?php echo $index === 0 ? 'class="active"' : ''; ?>></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-inner">
                        <?php foreach($article->images as $index => $image): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                                     class="d-block w-100" 
                                     alt="Article Image <?php echo $index + 1; ?>"
                                     style="max-height: 500px; object-fit: contain;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#articleCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#articleCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Article Content -->
            <div class="article-content mb-4">
                <?php echo nl2br(htmlspecialchars($article->content)); ?>
            </div>

            <!-- Download and Preview Buttons -->
            <div class="d-flex gap-2 mb-4">
                <a href="download_article.php?id=<?php echo $article->id; ?>" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download Article
                </a>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#previewModal">
                    <i class="fas fa-eye"></i> Preview Article
                </button>
            </div>

            <!-- Preview Modal -->
            <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="previewModalLabel"><?php echo htmlspecialchars($article->title); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="article-preview">
                                <?php echo nl2br(htmlspecialchars($article->content)); ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Article Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Page Number:</strong> <?php echo htmlspecialchars($article->page_number); ?></p>
                    <p><strong>Downloads:</strong> <?php echo $article->download_count; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
    echo "<div class='alert alert-danger'>Article not found.</div>";
}
?> 