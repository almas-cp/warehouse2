<!-- Dashboard Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Products</h5>
                        <h2 class="mb-0"><?= $stats['total_products'] ?></h2>
                    </div>
                    <i class="fas fa-boxes fa-3x"></i>
                </div>
                <p class="mt-2 mb-0">Total Items: <?= $stats['total_items'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Transactions</h5>
                        <h2 class="mb-0"><?= $stats['total_transactions'] ?></h2>
                    </div>
                    <i class="fas fa-exchange-alt fa-3x"></i>
                </div>
                <p class="mt-2 mb-0">Check-in: <?= $stats['checkin_count'] ?> / Check-out: <?= $stats['checkout_count'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card <?= $stats['low_stock_count'] > 0 ? 'bg-warning' : 'bg-info' ?> text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Low Stock Alert</h5>
                        <h2 class="mb-0"><?= $stats['low_stock_count'] ?></h2>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                </div>
                <p class="mt-2 mb-0">Products with quantity <= 5</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Inventory Management</h5>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
                        <button class="btn btn-light" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="inventoryTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Category</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)) : ?>
                            <tr>
                                <td colspan="7" class="text-center">No products found</td>
                            </tr>
                            <?php else : ?>
                                <?php foreach ($products as $product) : ?>
                                <tr>
                                    <td><?= $product['item_id'] ?></td>
                                    <td><?= $product['name'] ?></td>
                                    <td><?= number_format($product['unit_price'], 2) ?></td>
                                    <td><?= $product['quantity'] ?></td>
                                    <td><?= $product['category'] ?></td>
                                    <td><?= date('Y-m-d', strtotime($product['date_added'])) ?></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-info edit-product" data-id="<?= $product['item_id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-product" data-id="<?= $product['item_id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Recent Transactions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="transactionTable">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Benefactor</th>
                                <th>Date/Time</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_transactions)) : ?>
                            <tr>
                                <td colspan="7" class="text-center">No transactions found</td>
                            </tr>
                            <?php else : ?>
                                <?php foreach ($recent_transactions as $transaction) : ?>
                                <tr>
                                    <td><?= $transaction['transaction_id'] ?></td>
                                    <td><?= $transaction['product_name'] ?></td>
                                    <td>
                                        <?php if ($transaction['transaction_type'] == 'check-in') : ?>
                                            <span class="badge bg-success">Check In</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning">Check Out</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $transaction['quantity'] ?></td>
                                    <td><?= $transaction['benefactor'] ?></td>
                                    <td><?= date('Y-m-d H:i', strtotime($transaction['transaction_time'])) ?></td>
                                    <td><?= $transaction['notes'] ? $transaction['notes'] : '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="unit_price" class="form-label">Unit Price</label>
                        <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveProductBtn">Save Product</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="edit_item_id" name="item_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_unit_price" class="form-label">Unit Price</label>
                        <input type="number" step="0.01" class="form-control" id="edit_unit_price" name="unit_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="edit_quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="edit_category" name="category" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateProductBtn">Update Product</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this product?</p>
                <input type="hidden" id="delete_item_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Barcode Generation Modal -->
<div class="modal fade" id="barcodeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Barcode Generation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-info me-2" id="refreshBarcodesBtn">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                    <button class="btn btn-success" id="downloadBarcodesBtn">
                        <i class="fas fa-download me-1"></i>Download All
                    </button>
                </div>
                <div class="row" id="barcodeContainer">
                    <!-- Barcodes will be dynamically added here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Scan Modal -->
<div class="modal fade" id="scanModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batch Scan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Scanner Column -->
                    <div class="col-md-5">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Scanner</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <div id="scanner-container" style="position: relative; max-width: 100%;">
                                        <video id="video" style="width: 100%; height: 200px; border: 1px solid #ddd; border-radius: 4px;"></video>
                                        <canvas id="canvas" hidden></canvas>
                                        <div id="scanner-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.3); display: none;">
                                            <div class="d-flex justify-content-center align-items-center h-100">
                                                <div class="spinner-border text-light" role="status"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn btn-primary btn-sm" id="startScanBtn">
                                            <i class="fas fa-camera me-1"></i>Start Scanner
                                        </button>
                                        <button class="btn btn-secondary btn-sm d-none" id="stopScanBtn">
                                            <i class="fas fa-stop me-1"></i>Stop Scanner
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="card mt-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Alternative Methods</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <label for="manual_item_id" class="form-label">Enter Item ID:</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="manual_item_id">
                                                <button class="btn btn-outline-primary" type="button" id="addManualItemBtn">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label for="barcode_image" class="form-label">Upload Barcode:</label>
                                            <input type="file" class="form-control form-control-sm" id="barcode_image" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Batch Items Column -->
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Scanned Items</h6>
                                <button class="btn btn-sm btn-outline-light" id="clearAllItemsBtn">
                                    <i class="fas fa-trash me-1"></i>Clear All
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="batch_benefactor" class="form-label">Benefactor (for all items):</label>
                                    <input type="text" class="form-control" id="batch_benefactor" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="batch_notes" class="form-label">Notes (Optional):</label>
                                    <textarea class="form-control" id="batch_notes" rows="1"></textarea>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="batchItemsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Available</th>
                                                <th style="width: 120px;">Quantity</th>
                                                <th>Action</th>
                                                <th style="width: 50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Batch items will be added here dynamically -->
                                            <tr id="emptyBatchRow">
                                                <td colspan="6" class="text-center">No items scanned yet</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="processBatchBtn" disabled>
                    <i class="fas fa-check me-1"></i>Process All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Barcode Print Preview Modal -->
<div class="modal fade" id="printPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Labels</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Select the products you want to print labels for.
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAllProducts">
                                            </div>
                                        </th>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>Quantity to Print</th>
                                    </tr>
                                </thead>
                                <tbody id="printProductsBody">
                                    <!-- Products will be added here dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="printLabelsBtn">Print Selected Labels</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add Product
    $('#btnAddProduct').click(function() {
        $('#addProductForm')[0].reset();
        $('#addProductModal').modal('show');
    });
    
    $('#saveProductBtn').click(function() {
        if (!$('#addProductForm')[0].checkValidity()) {
            $('#addProductForm')[0].reportValidity();
            return;
        }
        
        $.ajax({
            url: baseUrl + 'dashboard/add_product',
            type: 'POST',
            data: $('#addProductForm').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#addProductModal').modal('hide');
                    window.location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
    
    // Edit Product
    $('.edit-product').click(function() {
        const itemId = $(this).data('id');
        
        $.ajax({
            url: baseUrl + 'dashboard/get_product/' + itemId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const product = response.data;
                    $('#edit_item_id').val(product.item_id);
                    $('#edit_name').val(product.name);
                    $('#edit_unit_price').val(product.unit_price);
                    $('#edit_quantity').val(product.quantity);
                    $('#edit_category').val(product.category);
                    $('#editProductModal').modal('show');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
    
    $('#updateProductBtn').click(function() {
        if (!$('#editProductForm')[0].checkValidity()) {
            $('#editProductForm')[0].reportValidity();
            return;
        }
        
        $.ajax({
            url: baseUrl + 'dashboard/update_product',
            type: 'POST',
            data: $('#editProductForm').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#editProductModal').modal('hide');
                    window.location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
    
    // Delete Product
    $('.delete-product').click(function() {
        const itemId = $(this).data('id');
        $('#delete_item_id').val(itemId);
        $('#deleteConfirmModal').modal('show');
    });
    
    $('#confirmDeleteBtn').click(function() {
        const itemId = $('#delete_item_id').val();
        
        $.ajax({
            url: baseUrl + 'dashboard/delete_product/' + itemId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#deleteConfirmModal').modal('hide');
                    window.location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
    
    // Search functionality
    $('#searchButton').click(function() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        
        $('#inventoryTable tbody tr').each(function() {
            const rowText = $(this).text().toLowerCase();
            if (rowText.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    $('#searchInput').on('keyup', function(e) {
        if (e.key === 'Enter') {
            $('#searchButton').click();
        }
    });
    
    // Barcode scanning implementation using ZXing
    let codeReader = null;
    let selectedDeviceId = null;
    let scannerActive = false;
    
    // Batch scanning variables
    let batchItems = {};
    
    $('#btnScan').click(function() {
        $('#scanModal').modal('show');
        $('#batch_benefactor').val('');
        $('#batch_notes').val('');
        $('#manual_item_id').val('');
        $('#barcode_image').val('');
        $('#startScanBtn').removeClass('d-none');
        $('#stopScanBtn').addClass('d-none');
        
        // Reset batch items
        batchItems = {};
        updateBatchTable();
    });
    
    // Add manual item button
    $('#addManualItemBtn').click(function() {
        const itemId = $('#manual_item_id').val().trim();
        if (itemId) {
            fetchProductInfoForBatch(itemId);
            $('#manual_item_id').val('');
        } else {
            alert('Please enter an item ID');
        }
    });
    
    $('#manual_item_id').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            $('#addManualItemBtn').click();
            e.preventDefault();
        }
    });
    
    // Clear all items
    $('#clearAllItemsBtn').click(function() {
        if (Object.keys(batchItems).length > 0) {
            if (confirm('Are you sure you want to clear all items?')) {
                batchItems = {};
                updateBatchTable();
            }
        }
    });
    
    // Handle barcode image upload
    $('#barcode_image').on('change', function(e) {
        if (e.target.files.length === 0) return;
        
        const file = e.target.files[0];
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                // Create canvas and draw image
                const canvas = document.getElementById('canvas');
                const ctx = canvas.getContext('2d');
                
                // Set canvas size to match image
                canvas.width = img.width;
                canvas.height = img.height;
                
                // Draw image to canvas
                ctx.drawImage(img, 0, 0, img.width, img.height);
                
                // Use ZXing to decode the barcode
                try {
                    const hints = new Map();
                    const formats = [
                        ZXing.BarcodeFormat.CODE_128,
                        ZXing.BarcodeFormat.CODE_39,
                        ZXing.BarcodeFormat.EAN_13,
                        ZXing.BarcodeFormat.EAN_8,
                        ZXing.BarcodeFormat.UPC_A,
                        ZXing.BarcodeFormat.UPC_E
                    ];
                    hints.set(ZXing.DecodeHintType.POSSIBLE_FORMATS, formats);
                    
                    // Create bitmap from canvas
                    const luminanceSource = new ZXing.HTMLCanvasElementLuminanceSource(canvas);
                    const binaryBitmap = new ZXing.BinaryBitmap(new ZXing.HybridBinarizer(luminanceSource));
                    
                    // Create reader and decode
                    const reader = new ZXing.MultiFormatReader();
                    reader.setHints(hints);
                    const result = reader.decode(binaryBitmap);
                    
                    if (result) {
                        // Play a beep sound
                        const beep = new Audio("data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU");
                        beep.play();
                        
                        // Get barcode text
                        const code = result.getText();
                        console.log("Decoded from image:", code);
                        
                        // Fetch product info for batch
                        fetchProductInfoForBatch(code);
                    }
                } catch (err) {
                    console.error("Error decoding barcode from image:", err);
                    alert("Could not find a valid barcode in the image. Please try another image or enter the code manually.");
                }
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        // Clear the input so the same file can be selected again
        $(this).val('');
    });
    
    $('#startScanBtn').click(function() {
        $(this).addClass('d-none');
        $('#stopScanBtn').removeClass('d-none');
        startZXingScanner();
    });
    
    $('#stopScanBtn').click(function() {
        $(this).addClass('d-none');
        $('#startScanBtn').removeClass('d-none');
        stopZXingScanner();
    });
    
    $('#scanModal').on('hidden.bs.modal', function() {
        stopZXingScanner();
    });
    
    async function startZXingScanner() {
        try {
            const hints = new Map();
            const formats = [
                ZXing.BarcodeFormat.CODE_128,
                ZXing.BarcodeFormat.CODE_39,
                ZXing.BarcodeFormat.EAN_13,
                ZXing.BarcodeFormat.EAN_8,
                ZXing.BarcodeFormat.UPC_A,
                ZXing.BarcodeFormat.UPC_E
            ];
            hints.set(ZXing.DecodeHintType.POSSIBLE_FORMATS, formats);
            
            if (!codeReader) {
                // Initialize the ZXing reader
                codeReader = new ZXing.BrowserMultiFormatReader(hints);
            }
            
            scannerActive = true;
            
            // Get video devices
            const videoInputDevices = await codeReader.listVideoInputDevices();
            
            if (videoInputDevices.length === 0) {
                alert('No camera found on your device');
                $('#startScanBtn').removeClass('d-none');
                $('#stopScanBtn').addClass('d-none');
                return;
            }
            
            // Select the rear camera if available, otherwise use the first camera
            selectedDeviceId = null;
            for (const device of videoInputDevices) {
                if (/back|rear|environment/gi.test(device.label)) {
                    selectedDeviceId = device.deviceId;
                    break;
                }
            }
            if (!selectedDeviceId) {
                selectedDeviceId = videoInputDevices[0].deviceId;
            }
            
            // Start decoding from the device with the selected ID
            const videoElement = document.getElementById('video');
            
            // Handle successful scanning
            codeReader.decodeFromVideoDevice(selectedDeviceId, videoElement, (result, error) => {
                if (result && scannerActive) {
                    // Play a beep sound
                    const beep = new Audio("data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU");
                    beep.play();
                    
                    // Get the barcode text
                    const code = result.getText();
                    console.log("Scanned code:", code);
                    
                    // Stop scanning temporarily
                    scannerActive = false;
                    $('#scanner-overlay').show();
                    
                    // Fetch product info for batch
                    fetchProductInfoForBatch(code);
                }
                
                if (error && !(error instanceof ZXing.NotFoundException)) {
                    console.error("Scanner error:", error);
                }
            }).catch(err => {
                console.error("Camera error:", err);
                alert('Error accessing camera: ' + err.message);
                $('#startScanBtn').removeClass('d-none');
                $('#stopScanBtn').addClass('d-none');
            });
            
        } catch (err) {
            console.error('Error initializing scanner:', err);
            alert('Error initializing scanner: ' + err.message);
            $('#startScanBtn').removeClass('d-none');
            $('#stopScanBtn').addClass('d-none');
        }
    }
    
    function stopZXingScanner() {
        if (codeReader) {
            try {
                codeReader.reset();
                scannerActive = false;
            } catch (err) {
                console.error('Error stopping scanner:', err);
            }
        }
    }
    
    // Fetch product info for batch scanning
    function fetchProductInfoForBatch(itemId) {
        console.log("Fetching product info for batch:", itemId);
        
        // If already in batch, don't fetch again, just increase quantity
        if (batchItems[itemId]) {
            const quantity = batchItems[itemId].quantity + 1;
            batchItems[itemId].quantity = quantity;
            updateBatchTable();
            
            // Enable scanning again
            $('#scanner-overlay').hide();
            setTimeout(function() {
                scannerActive = true;
            }, 1000);
            return;
        }
        
        $.ajax({
            url: baseUrl + 'dashboard/get_product/' + itemId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#scanner-overlay').hide();
                
                if (response.status === 'success') {
                    const product = response.data;
                    
                    // Add to batch items
                    batchItems[product.item_id] = {
                        id: product.item_id,
                        name: product.name,
                        available: product.quantity,
                        quantity: 1,
                        action: 'check-out' // Default action
                    };
                    
                    updateBatchTable();
                    
                    // Enable scanning again after a short delay
                    setTimeout(function() {
                        scannerActive = true;
                    }, 1000);
                    
                } else {
                    alert('Error: Product not found');
                    setTimeout(function() {
                        scannerActive = true;
                    }, 1000);
                }
            },
            error: function(xhr) {
                $('#scanner-overlay').hide();
                console.error("AJAX error:", xhr.responseText);
                alert('Error: Could not fetch product information');
                setTimeout(function() {
                    scannerActive = true;
                }, 1000);
            }
        });
    }
    
    // Update the batch table with current items
    function updateBatchTable() {
        const itemCount = Object.keys(batchItems).length;
        
        // Enable/disable process button
        $('#processBatchBtn').prop('disabled', itemCount === 0 || $('#batch_benefactor').val().trim() === '');
        
        // Show/hide empty message
        if (itemCount === 0) {
            $('#emptyBatchRow').show();
        } else {
            $('#emptyBatchRow').hide();
        }
        
        // Remove existing batch rows (except empty row)
        $('#batchItemsTable tbody tr:not(#emptyBatchRow)').remove();
        
        // Add current batch items
        for (const itemId in batchItems) {
            const item = batchItems[itemId];
            // Display quantity as positive/negative based on action
            const displayQuantity = item.action === 'check-out' ? -item.quantity : item.quantity;
            const textColor = item.action === 'check-out' ? 'text-danger' : 'text-success';
            
            const rowHtml = `
                <tr data-id="${item.id}">
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.available}</td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control item-quantity ${textColor}" 
                                min="1" max="${item.action === 'check-out' ? item.available : 999}" 
                                value="${Math.abs(displayQuantity)}" style="width: 80px;">
                            <span class="input-group-text ${textColor}">
                                ${item.action === 'check-out' ? '-' : '+'}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn ${item.action === 'check-out' ? 'btn-danger active' : 'btn-outline-danger'} btn-action" 
                                data-action="check-out" data-id="${item.id}">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn ${item.action === 'check-in' ? 'btn-success active' : 'btn-outline-success'} btn-action" 
                                data-action="check-in" data-id="${item.id}">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger remove-item" data-id="${item.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#batchItemsTable tbody').append(rowHtml);
        }
        
        // Attach event handlers
        $('.btn-action').off('click').on('click', function() {
            const itemId = $(this).data('id');
            const action = $(this).data('action');
            
            if (batchItems[itemId]) {
                batchItems[itemId].action = action;
                updateBatchTable();
            }
        });
        
        $('.item-quantity').off('change').on('change', function() {
            const itemId = $(this).closest('tr').data('id');
            const quantity = parseInt($(this).val()) || 1;
            
            if (batchItems[itemId]) {
                // Ensure quantity is valid
                const maxQuantity = batchItems[itemId].action === 'check-out' ? 
                    batchItems[itemId].available : 999;
                    
                batchItems[itemId].quantity = Math.min(Math.max(1, quantity), maxQuantity);
                $(this).val(batchItems[itemId].quantity);
            }
        });
        
        $('.remove-item').off('click').on('click', function() {
            const itemId = $(this).data('id');
            delete batchItems[itemId];
            updateBatchTable();
        });
    }
    
    // Process all items in batch
    $('#processBatchBtn').click(function() {
        const benefactor = $('#batch_benefactor').val().trim();
        const notes = $('#batch_notes').val().trim();
        
        if (!benefactor) {
            alert('Please enter a benefactor name');
            return;
        }
        
        if (Object.keys(batchItems).length === 0) {
            alert('No items in batch');
            return;
        }
        
        // Create an array of transactions to process
        const transactions = [];
        for (const itemId in batchItems) {
            const item = batchItems[itemId];
            transactions.push({
                item_id: item.id,
                transaction_type: item.action,
                quantity: Math.abs(item.quantity), // Always send positive quantity
                benefactor: benefactor,
                notes: notes
            });
        }
        
        // Disable the process button
        $('#processBatchBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        // Process each transaction
        let completed = 0;
        let errors = 0;
        
        function processBatch(index) {
            if (index >= transactions.length) {
                // All transactions processed
                if (errors === 0) {
                    // Success!
                    alert('All transactions processed successfully!');
                    $('#scanModal').modal('hide');
                    window.location.reload(); // Refresh the page
                } else {
                    // Some errors
                    alert(`Completed ${completed} transactions with ${errors} errors`);
                    $('#processBatchBtn').prop('disabled', false).html('<i class="fas fa-check me-1"></i>Process All');
                }
                return;
            }
            
            const transaction = transactions[index];
            
            $.ajax({
                url: baseUrl + 'dashboard/add_transaction',
                type: 'POST',
                data: transaction,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        completed++;
                    } else {
                        errors++;
                        console.error("Transaction error:", response.message);
                    }
                    processBatch(index + 1);
                },
                error: function(xhr) {
                    errors++;
                    console.error("AJAX error:", xhr.responseText);
                    processBatch(index + 1);
                }
            });
        }
        
        // Start processing
        processBatch(0);
    });
    
    // Monitor benefactor field for process button state
    $('#batch_benefactor').on('input', function() {
        updateBatchTable(); // This will update the process button state
    });
    
    // Barcode generation enhancements
    $('#btnGenerateBarcodes').click(function() {
        $('#barcodeModal').modal('show');
        loadBarcodes();
    });
    
    // Print labels functionality
    $('#barcodeModal').on('click', '.print-label', function() {
        const itemId = $(this).data('id');
        printSingleLabel(itemId);
    });
    
    function printSingleLabel(itemId) {
        const printWindow = window.open('', '_blank');
        
        if (!printWindow) {
            alert('Please allow pop-ups to print labels');
            return;
        }
        
        $.ajax({
            url: baseUrl + 'dashboard/generate_barcode/' + itemId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const product = response.data;
                    
                    let html = `
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <title>Label - ${product.name}</title>
                            <style>
                                body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                                .label { 
                                    width: 300px; 
                                    text-align: center; 
                                    border: 1px solid #ddd; 
                                    border-radius: 4px; 
                                    padding: 15px; 
                                    margin: 20px auto;
                                }
                                img { max-width: 100%; }
                                h3 { margin-top: 0; }
                                .no-print { margin: 20px; text-align: center; }
                                @media print {
                                    .no-print { display: none; }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="no-print">
                                <button onclick="window.print()">Print Label</button>
                            </div>
                            <div class="label">
                                <h3>${product.name}</h3>
                                <img src="${product.barcode_url}" alt="Barcode ${product.item_id}">
                                <p>ID: ${product.item_id}</p>
                            </div>
                        </body>
                        </html>
                    `;
                    
                    printWindow.document.write(html);
                    printWindow.document.close();
                    
                } else {
                    alert('Error generating label');
                    printWindow.close();
                }
            },
            error: function() {
                alert('Error generating label');
                printWindow.close();
            }
        });
    }
    
    // Add print buttons to each barcode card
    function loadBarcodes() {
        $('#barcodeContainer').html('<div class="text-center w-100"><div class="spinner-border" role="status"></div></div>');
        
        $.ajax({
            url: baseUrl + 'dashboard/get_all_products',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let barcodeHtml = '';
                    
                    $.each(response.data, function(index, product) {
                        barcodeHtml += `
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6>${product.name}</h6>
                                        <img src="https://barcodeapi.org/api/code128/${product.item_id}" 
                                             alt="Barcode ${product.item_id}" class="img-fluid barcode-img" 
                                             data-id="${product.item_id}" data-name="${product.name}">
                                        <p class="mt-2">ID: ${product.item_id}</p>
                                        <button class="btn btn-sm btn-info print-label" data-id="${product.item_id}">
                                            <i class="fas fa-print me-1"></i>Print Label
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    $('#barcodeContainer').html(barcodeHtml);
                } else {
                    $('#barcodeContainer').html('<div class="alert alert-danger">Error loading barcodes</div>');
                }
            },
            error: function() {
                $('#barcodeContainer').html('<div class="alert alert-danger">Error loading barcodes</div>');
            }
        });
    }
    
    $('#refreshBarcodesBtn').click(function() {
        loadBarcodes();
    });
    
    // Download all barcodes
    $('#downloadBarcodesBtn').click(function() {
        // Create a temporary container for printing
        const printWindow = window.open('', '_blank');
        
        if (!printWindow) {
            alert('Please allow pop-ups to download barcodes');
            return;
        }
        
        // Get all products
        $.ajax({
            url: baseUrl + 'dashboard/get_all_products',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Create the HTML content with barcodes
                    let html = `
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <title>Warehouse Barcodes</title>
                            <style>
                                body { font-family: Arial, sans-serif; }
                                .container { display: flex; flex-wrap: wrap; }
                                .barcode-item { 
                                    width: 33%; 
                                    text-align: center; 
                                    padding: 10px; 
                                    box-sizing: border-box;
                                    page-break-inside: avoid;
                                }
                                .barcode-card {
                                    border: 1px solid #ddd;
                                    border-radius: 4px;
                                    padding: 15px;
                                    margin-bottom: 15px;
                                }
                                h3 { margin-top: 0; }
                                img { max-width: 100%; }
                                @media print {
                                    .no-print { display: none; }
                                    body { margin: 0; padding: 15px; }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="no-print" style="text-align: center; margin: 20px 0;">
                                <h1>Warehouse Barcodes</h1>
                                <button onclick="window.print()">Print Barcodes</button>
                                <p>Click the button above to print or right-click and select Save to download as PDF</p>
                            </div>
                            <div class="container">
                    `;
                    
                    // Add each product barcode
                    $.each(response.data, function(index, product) {
                        html += `
                            <div class="barcode-item">
                                <div class="barcode-card">
                                    <h3>${product.name}</h3>
                                    <img src="https://barcodeapi.org/api/code128/${product.item_id}" 
                                         alt="Barcode ${product.item_id}">
                                    <p>ID: ${product.item_id}</p>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += `
                            </div>
                        </body>
                        </html>
                    `;
                    
                    // Write the HTML to the new window
                    printWindow.document.write(html);
                    printWindow.document.close();
                    
                } else {
                    alert('Error loading barcodes for download');
                    printWindow.close();
                }
            },
            error: function() {
                alert('Error loading barcodes for download');
                printWindow.close();
            }
        });
    });
});
</script>