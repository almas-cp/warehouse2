    </div><!-- End container -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- ZXing barcode scanning library -->
    <script src="https://unpkg.com/@zxing/library@latest"></script>
    
    <!-- App JS -->
    <script>
        const baseUrl = '<?= base_url() ?>';
        
        // Global loading indicator
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }
        
        // Toast notifications
        function showToast(message, type = 'success', duration = 3000) {
            const toastId = 'toast-' + Date.now();
            const bgClass = type === 'success' ? 'bg-success' : 
                           type === 'error' ? 'bg-danger' : 
                           type === 'warning' ? 'bg-warning' : 'bg-info';
            
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            $('.toast-container').append(toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: duration
            });
            
            toast.show();
            
            // Remove the toast element from DOM after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }
        
        // Throttle function to limit function calls
        function throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }
        
        // Debounce function to delay function calls
        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            };
        }
        
        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2
            }).format(amount);
        }
        
        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            }).format(date);
        }
        
        // Format datetime
        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        }
        
        // Page load complete
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Set all AJAX requests to show loading indicator
            $(document).ajaxStart(function() {
                showLoading();
            }).ajaxStop(function() {
                hideLoading();
            }).ajaxError(function(event, jqXHR, settings, error) {
                showToast('Error: ' + (jqXHR.responseJSON?.message || error || 'Unknown error'), 'error');
            });
        });
    </script>
</body>
</html> 