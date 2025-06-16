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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div id="scanner-container">
                        <p>Barcode scanner will be implemented in Phase 3</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="manual_item_id" class="form-label">Or Enter Item ID Manually:</label>
                    <input type="text" class="form-control" id="manual_item_id">
                </div>
                <div id="scanned-product-info" class="d-none">
                    <div class="alert alert-info">
                        <h5 id="product-name"></h5>
                        <p>ID: <span id="product-id"></span></p>
                        <p>Current Quantity: <span id="product-quantity"></span></p>
                    </div>
                    <form id="transactionForm">
                        <input type="hidden" id="transaction_item_id" name="item_id">
                        <div class="mb-3">
                            <label class="form-label">Transaction Type:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transaction_type" id="type_checkout" value="check-out" checked>
                                <label class="form-check-label" for="type_checkout">
                                    Check Out (Remove from Inventory)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transaction_type" id="type_checkin" value="check-in">
                                <label class="form-check-label" for="type_checkin">
                                    Check In (Add to Inventory)
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="transaction_quantity" class="form-label">Quantity:</label>
                            <input type="number" min="1" class="form-control" id="transaction_quantity" name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="benefactor" class="form-label">Benefactor:</label>
                            <input type="text" class="form-control" id="benefactor" name="benefactor" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional):</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-none" id="processTransactionBtn">Process Transaction</button>
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
    
    // Barcode generation
    $('#btnGenerateBarcodes').click(function() {
        $('#barcodeModal').modal('show');
        loadBarcodes();
    });
    
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
                                             alt="Barcode ${product.item_id}" class="img-fluid">
                                        <p class="mt-2">ID: ${product.item_id}</p>
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
    
    // Scanner functionality - phase 3 implementation
    $('#btnScan').click(function() {
        $('#scanModal').modal('show');
        $('#scanned-product-info').addClass('d-none');
        $('#processTransactionBtn').addClass('d-none');
        $('#manual_item_id').val('');
    });
    
    $('#manual_item_id').change(function() {
        const itemId = $(this).val();
        if (itemId) {
            fetchProductInfo(itemId);
        }
    });
    
    function fetchProductInfo(itemId) {
        $.ajax({
            url: baseUrl + 'dashboard/get_product/' + itemId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const product = response.data;
                    $('#product-name').text(product.name);
                    $('#product-id').text(product.item_id);
                    $('#product-quantity').text(product.quantity);
                    $('#transaction_item_id').val(product.item_id);
                    $('#transaction_quantity').val(1);
                    $('#benefactor').val('');
                    $('#notes').val('');
                    
                    $('#scanned-product-info').removeClass('d-none');
                    $('#processTransactionBtn').removeClass('d-none');
                } else {
                    alert('Error: Product not found');
                }
            },
            error: function() {
                alert('Error: Could not fetch product information');
            }
        });
    }
});
</script> 