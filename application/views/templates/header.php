<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Warehouse Inventory System' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Preload essential resources -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://barcodeapi.org">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            padding-top: 20px;
            background-color: var(--light-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-brand {
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand i {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }
        
        .action-buttons .btn {
            margin-right: 5px;
            transition: all 0.2s ease-in-out;
        }
        
        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .table th {
            background-color: #f1f1f1;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table-responsive {
            overflow-x: auto;
            scrollbar-width: thin;
        }
        
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }
        
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .modal-header {
            background-color: var(--light-color);
            border-bottom: 1px solid #dee2e6;
        }
        
        .navbar {
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 0.5rem;
        }
        
        .card {
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card-header {
            border-top-left-radius: 0.5rem !important;
            border-top-right-radius: 0.5rem !important;
        }
        
        /* Loading animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255,255,255,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.3s;
        }
        
        .loading-overlay.active {
            visibility: visible;
            opacity: 1;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(0,0,0,0.1);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            min-width: 250px;
        }
        
        /* Dashboard cards */
        .dashboard-card {
            transition: all 0.3s ease;
            border: none;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .dashboard-card .card-body {
            padding: 1.5rem;
        }
        
        .dashboard-card i {
            opacity: 0.8;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }
            
            .dashboard-card {
                margin-bottom: 1rem;
            }
            
            .modal-dialog {
                margin: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>
    
    <!-- Toast container -->
    <div class="toast-container"></div>
    
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= base_url() ?>">
                    <i class="fas fa-warehouse"></i>Warehouse Inventory
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <button class="btn btn-outline-primary" id="btnAddProduct">
                                <i class="fas fa-plus me-1"></i><span class="d-none d-md-inline">Add Product</span>
                            </button>
                        </li>
                        <li class="nav-item ms-2">
                            <button class="btn btn-outline-secondary" id="btnGenerateBarcodes">
                                <i class="fas fa-barcode me-1"></i><span class="d-none d-md-inline">Barcodes</span>
                            </button>
                        </li>
                        <li class="nav-item ms-2">
                            <button class="btn btn-outline-success" id="btnScan">
                                <i class="fas fa-camera me-1"></i><span class="d-none d-md-inline">Scan</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 