    </div> <!-- Close container from header -->
    
    <!-- Footer -->
    <footer class="bg-white mt-5 py-4 border-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="text-muted small">
                        <i class="fas fa-copyright me-1"></i> <?php echo date('Y'); ?> ExamFlow. All rights reserved.
                    </span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-decoration-none text-muted small me-3">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted small me-3">Terms of Service</a>
                    <a href="#" class="text-decoration-none text-muted small">Help</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: Add any custom JavaScript -->
    <script>
        // Add active class to current navigation
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = window.location.pathname;
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            
            navLinks.forEach(link => {
                if(link.getAttribute('href') === currentLocation.split('/').pop()) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>