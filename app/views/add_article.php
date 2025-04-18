<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New Article</h4>
            </div>
            <div class="card-body">
                <form action="/article_store/public/process_article.php" method="POST" enctype="multipart/form-data" id="article-form">
                    <!-- Image Upload -->
                    <div class="mb-4">
                        <label class="form-label">Article Image</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            <button class="btn btn-outline-secondary" type="button" id="preview-btn">Preview</button>
                        </div>
                        <div id="image-preview" class="mt-2" style="display: none;">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <!-- Date -->
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <!-- Author -->
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <div class="input-group">
                            <select class="form-select select2" id="author" name="author" required>
                                <option value="">Select Author</option>
                                <?php
                                $stmt = $db->query("SELECT * FROM authors ORDER BY name");
                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAuthorModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Pen Name -->
                    <div class="mb-3">
                        <label for="pen_name" class="form-label">Pen Name</label>
                        <div class="input-group">
                            <select class="form-select select2" id="pen_name" name="pen_name">
                                <option value="">Select Pen Name</option>
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addPenNameModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Subjects -->
                    <div class="mb-3">
                        <label for="subjects" class="form-label">Subjects</label>
                        <div class="input-group">
                            <select class="form-select select2" id="subjects" name="subjects[]" multiple>
                                <?php
                                $stmt = $db->query("SELECT * FROM subjects ORDER BY name");
                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Page Number -->
                    <div class="mb-3">
                        <label for="page_number" class="form-label">Page Number</label>
                        <input type="number" class="form-control" id="page_number" name="page_number" required>
                    </div>

                    <!-- Related Media -->
                    <div class="mb-3">
                        <label for="related_media" class="form-label">Related Media</label>
                        <div class="input-group">
                            <select class="form-select select2" id="related_media" name="related_media[]" multiple>
                                <?php
                                $stmt = $db->query("SELECT * FROM media ORDER BY title");
                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$row['id']}'>{$row['title']} ({$row['type']})</option>";
                                }
                                ?>
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMediaModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               placeholder="Enter tags separated by commas">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Save Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Author Modal -->
<div class="modal fade" id="addAuthorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Author</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addAuthorForm">
                    <div class="mb-3">
                        <label for="new_author_name" class="form-label">Author Name</label>
                        <input type="text" class="form-control" id="new_author_name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveAuthor">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Pen Name Modal -->
<div class="modal fade" id="addPenNameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Pen Names</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new_pen_name" class="form-label">Add New Pen Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="new_pen_name" required>
                        <button type="button" class="btn btn-primary" id="savePenName">Add</button>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Existing Pen Names</h6>
                    <div id="penNamesList" class="list-group">
                        <!-- Pen names will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Subjects</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new_subject_name" class="form-label">Add New Subject</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="new_subject_name" required>
                        <button type="button" class="btn btn-primary" id="saveSubject">Add</button>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Existing Subjects</h6>
                    <div id="subjectsList" class="list-group">
                        <!-- Subjects will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Media Modal -->
<div class="modal fade" id="addMediaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new_media_title" class="form-label">Add New Media</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="new_media_title" placeholder="Title" required>
                    </div>
                    <div class="input-group">
                        <select class="form-select" id="new_media_type" required>
                            <option value="book">Book</option>
                            <option value="article">Article</option>
                            <option value="video">Video</option>
                            <option value="audio">Audio</option>
                        </select>
                        <button type="button" class="btn btn-primary" id="saveMedia">Add</button>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Existing Media</h6>
                    <div id="mediaList" class="list-group">
                        <!-- Media items will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Image preview
    $('#preview-btn').click(function() {
        const file = $('#image')[0].files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview img').attr('src', e.target.result);
                $('#image-preview').show();
            }
            reader.readAsDataURL(file);
        } else {
            showAlert('Please select an image first', 'warning');
        }
    });

    // Auto preview when file is selected
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview img').attr('src', e.target.result);
                $('#image-preview').show();
            }
            reader.readAsDataURL(file);
        }
    });

    // Load pen names based on selected author
    $('#author').change(function() {
        var authorId = $(this).val();
        if(authorId) {
            $.ajax({
                url: '/article_store/public/get_pen_names.php',
                type: 'POST',
                data: {author_id: authorId},
                success: function(data) {
                    $('#pen_name').html(data);
                }
            });
        } else {
            $('#pen_name').html('<option value="">Select Pen Name</option>');
        }
    });

    // Load pen names list
    function loadPenNames() {
        var authorId = $('#author').val();
        if(authorId) {
            $.ajax({
                url: '/article_store/public/get_pen_names.php',
                type: 'POST',
                data: {author_id: authorId},
                success: function(data) {
                    $('#penNamesList').html(data);
                }
            });
        }
    }

    // Load subjects list
    function loadSubjects() {
        $.ajax({
            url: '/article_store/public/get_subjects.php',
            type: 'GET',
            success: function(data) {
                $('#subjectsList').html(data);
            }
        });
    }

    // Load media list
    function loadMedia() {
        $.ajax({
            url: '/article_store/public/get_media.php',
            type: 'GET',
            success: function(data) {
                $('#mediaList').html(data);
            }
        });
    }

    // Add new author
    $('#saveAuthor').click(function() {
        var name = $('#new_author_name').val();
        if(name) {
            $.ajax({
                url: '/article_store/public/add_author.php',
                type: 'POST',
                data: {name: name},
                success: function(response) {
                    if(response.success) {
                        $('#author').append(new Option(name, response.id));
                        $('#author').val(response.id).trigger('change');
                        $('#addAuthorModal').modal('hide');
                        $('#new_author_name').val('');
                        
                        // Show success message
                        showAlert('Author added successfully!', 'success');
                    } else {
                        showAlert(response.error || 'Failed to add author', 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('Error: ' + error, 'danger');
                }
            });
        } else {
            showAlert('Please enter an author name', 'warning');
        }
    });

    // Add new pen name
    $('#savePenName').click(function() {
        var name = $('#new_pen_name').val();
        var authorId = $('#author').val();
        if(name && authorId) {
            $.ajax({
                url: '/article_store/public/pen_name_operations.php',
                type: 'POST',
                data: {
                    action: 'add',
                    name: name,
                    author_id: authorId
                },
                success: function(response) {
                    if(response.success) {
                        // Add the new pen name to the dropdown
                        $('#pen_name').append(new Option(name, response.id));
                        $('#pen_name').val(response.id).trigger('change');
                        
                        // Reload the pen names list
                        loadPenNames();
                        
                        // Clear the input
                        $('#new_pen_name').val('');
                        
                        // Show success message
                        showAlert('Pen name added successfully!', 'success');
                    } else {
                        showAlert(response.error || 'Failed to add pen name', 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('Error: ' + error, 'danger');
                }
            });
        } else {
            showAlert('Please enter a pen name and select an author', 'warning');
        }
    });

    // Add new subject
    $('#saveSubject').click(function() {
        var name = $('#new_subject_name').val();
        if(name) {
            $.ajax({
                url: '/article_store/public/subject_operations.php',
                type: 'POST',
                data: {
                    action: 'add',
                    name: name
                },
                success: function(response) {
                    if(response.success) {
                        loadSubjects();
                        $('#new_subject_name').val('');
                        showAlert('Subject added successfully!', 'success');
                    } else {
                        showAlert(response.error || 'Failed to add subject', 'danger');
                    }
                }
            });
        }
    });

    // Add new media
    $('#saveMedia').click(function() {
        var title = $('#new_media_title').val();
        var type = $('#new_media_type').val();
        if(title && type) {
            $.ajax({
                url: '/article_store/public/media_operations.php',
                type: 'POST',
                data: {
                    action: 'add',
                    title: title,
                    type: type
                },
                success: function(response) {
                    if(response.success) {
                        loadMedia();
                        $('#new_media_title').val('');
                        showAlert('Media added successfully!', 'success');
                    } else {
                        showAlert(response.error || 'Failed to add media', 'danger');
                    }
                }
            });
        }
    });

    // Load lists when modals are shown
    $('#addPenNameModal').on('show.bs.modal', function() {
        loadPenNames();
    });

    $('#addSubjectModal').on('show.bs.modal', function() {
        loadSubjects();
    });

    $('#addMediaModal').on('show.bs.modal', function() {
        loadMedia();
    });

    // Function to show alert messages
    function showAlert(message, type) {
        var alertDiv = $('<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>');
        
        $('.container').prepend(alertDiv);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            alertDiv.alert('close');
        }, 5000);
    }

    // Handle form submission
    $('#article-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        // Show loading state
        var submitButton = $(this).find('button[type="submit"]');
        var originalText = submitButton.html();
        submitButton.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
        
        // First upload the image
        var imageFile = $('#image')[0].files[0];
        if (imageFile) {
            var imageFormData = new FormData();
            imageFormData.append('file', imageFile);
            
            $.ajax({
                url: '/article_store/public/upload.php',
                type: 'POST',
                data: imageFormData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Add the image path to the form data
                        formData.append('image_path', response.filepath);
                        
                        // Now submit the article form
                        $.ajax({
                            url: '/article_store/public/process_article.php',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.success) {
                                    showAlert('Article saved successfully!', 'success');
                                    setTimeout(function() {
                                        window.location.href = '/?page=home';
                                    }, 2000);
                                } else {
                                    showAlert(response.error || 'Failed to save article', 'danger');
                                    submitButton.prop('disabled', false).html(originalText);
                                }
                            },
                            error: function(xhr, status, error) {
                                showAlert('Error: ' + error, 'danger');
                                submitButton.prop('disabled', false).html(originalText);
                            }
                        });
                    } else {
                        showAlert(response.error || 'Failed to upload image', 'danger');
                        submitButton.prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('Error: ' + error, 'danger');
                    submitButton.prop('disabled', false).html(originalText);
                }
            });
        } else {
            showAlert('Please select an image', 'warning');
            submitButton.prop('disabled', false).html(originalText);
        }
    });
});
</script> 