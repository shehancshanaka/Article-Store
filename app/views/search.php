<?php
// Get all authors for dropdown
$authorQuery = "SELECT id, name FROM authors ORDER BY name";
$authorStmt = $db->query($authorQuery);
$authors = $authorStmt->fetchAll(PDO::FETCH_ASSOC);

// Get all subjects for dropdown
$subjectQuery = "SELECT id, name FROM subjects ORDER BY name";
$subjectStmt = $db->query($subjectQuery);
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

// Get all media for dropdown
$mediaQuery = "SELECT id, title FROM media ORDER BY title";
$mediaStmt = $db->query($mediaQuery);
$media = $mediaStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Search Articles</h4>
            </div>
            <div class="card-body">
                <form id="searchForm" method="post" action="/article_store/public/search_articles.php">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter article title">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="author" class="form-label">Author</label>
                            <select class="form-select" id="author" name="author">
                                <option value="">Select Author</option>
                                <?php foreach ($authors as $author): ?>
                                    <option value="<?php echo $author['id']; ?>"><?php echo htmlspecialchars($author['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <select class="form-select" id="subject" name="subject">
                                <option value="">Select Subject</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?php echo $subject['id']; ?>"><?php echo htmlspecialchars($subject['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="media" class="form-label">Related Media</label>
                            <select class="form-select" id="media" name="media">
                                <option value="">Select Media</option>
                                <?php foreach ($media as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="date_from" name="date_from">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date" class="form-control" id="date_to" name="date_to">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <button type="reset" class="btn btn-secondary">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="searchResults" class="row mt-4">
    <!-- Search results will be displayed here -->
</div>

<script>
$(document).ready(function() {
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        $('#searchResults').html('<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
        // Submit form via AJAX
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#searchResults').html(response);
            },
            error: function(xhr, status, error) {
                $('#searchResults').html('<div class="col-12"><div class="alert alert-danger">Error: ' + error + '</div></div>');
            }
        });
    });
});
</script> 