$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle image preview in article form
    $('#article-image').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).fadeIn();
            }
            reader.readAsDataURL(file);
        }
    });

    // Handle tag input
    $('#tags').on('keyup', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            var tag = $(this).val().trim();
            if (tag) {
                addTag(tag);
                $(this).val('');
            }
        }
    });

    // Add tag function
    function addTag(tag) {
        var tagHtml = `
            <span class="tag">
                ${tag}
                <span class="tag-remove">&times;</span>
            </span>
        `;
        $('.tag-input').append(tagHtml);
    }

    // Remove tag
    $(document).on('click', '.tag-remove', function() {
        $(this).parent().remove();
    });

    // Handle form submission with loading state
    $('#article-form').on('submit', function() {
        var submitButton = $(this).find('button[type="submit"]');
        var originalText = submitButton.html();
        
        submitButton.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    });

    // Handle search results loading
    function showLoadingSpinner() {
        $('#search-results').html(`
            <div class="loading-spinner active">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
    }

    // Smooth scroll to top
    $('.back-to-top').click(function() {
        $('html, body').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

    // Show/hide back to top button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });

    // Initialize image preview modal
    $('.article-thumbnail').click(function() {
        var imageUrl = $(this).attr('src');
        $('#imageModal').find('.modal-body img').attr('src', imageUrl);
        $('#imageModal').modal('show');
    });

    // Handle dynamic form fields
    $('.add-field').click(function() {
        var fieldType = $(this).data('field-type');
        var fieldHtml = '';
        
        switch(fieldType) {
            case 'author':
                fieldHtml = `
                    <div class="mb-3">
                        <input type="text" class="form-control" name="new_authors[]" placeholder="New Author Name">
                    </div>
                `;
                break;
            case 'subject':
                fieldHtml = `
                    <div class="mb-3">
                        <input type="text" class="form-control" name="new_subjects[]" placeholder="New Subject">
                    </div>
                `;
                break;
        }
        
        $(this).before(fieldHtml);
    });

    // Add smooth animations to cards
    $('.card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
}); 