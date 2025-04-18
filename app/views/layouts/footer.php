    </div> <!-- End container -->

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Martin Wickramasinghe Trust</h5>
                    <p>Preserving and sharing the literary heritage of Sri Lanka</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Martin Wickramasinghe Trust. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Dropzone JS -->
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    
    <!-- Custom JS -->
    <script src="/assets/js/main.js"></script>
    
    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            
            // Initialize Dropzone
            Dropzone.autoDiscover = false;
            if (document.getElementById('article-image')) {
                new Dropzone("#article-image", {
                    url: "/article_store/public/upload.php",
                    maxFiles: 1,
                    acceptedFiles: "image/*",
                    addRemoveLinks: true,
                    dictDefaultMessage: "Drop article image here or click to upload",
                    success: function(file, response) {
                        if (response.success) {
                            document.querySelector('input[name="image_path"]').value = response.filepath;
                        }
                    }
                });
            }
        });
    </script>
</body>
</html> 