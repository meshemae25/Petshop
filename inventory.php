<?php
// Sample product data - in a real application this would come from a database
$products = [
    [
        'id' => 1,
        'image' => 'dog-food.jpg',
        'name' => 'Premium Dog Food',
        'category' => 'Food',
        'sku' => 'PF-001',
        'stock' => 1000,
        'price' => 2500,
        'discount' => 15,
        'status' => 'Active'
    ],
    [
        'id' => 2,
        'image' => 'cat-bed.jpg',
        'name' => 'Luxury Cat Bed',
        'category' => 'Accessories',
        'sku' => 'CB-002',
        'stock' => 0,
        'price' => 1350,
        'discount' => 0,
        'status' => 'Out of Stock'
    ],
    [
        'id' => 3,
        'image' => 'dog-toy.jpg',
        'name' => 'Interactive Dog Toy',
        'category' => 'Toys',
        'sku' => 'DT-003',
        'stock' => 43,
        'price' => 850,
        'discount' => 10,
        'status' => 'Active'
    ],
    [
        'id' => 4,
        'image' => 'cat-food.jpg',
        'name' => 'Organic Cat Food',
        'category' => 'Food',
        'sku' => 'CF-004',
        'stock' => 12,
        'price' => 1900,
        'discount' => 0,
        'status' => 'Low Stock'
    ],
    [
        'id' => 5,
        'image' => 'pet-shampoo.jpg',
        'name' => 'Gentle Pet Shampoo',
        'category' => 'Grooming',
        'sku' => 'GP-005',
        'stock' => 87,
        'price' => 750,
        'discount' => 5,
        'status' => 'Active'
    ]
];

// Helper function to format price
function formatPrice($price) {
    return '₱' . number_format($price, 2);
}

// Helper function to get status CSS class
function getStatusClass($status) {
    $statusClasses = [
        'Active' => 'bg-success',
        'Low Stock' => 'bg-warning',
        'Out of Stock' => 'bg-danger',
        'Discontinued' => 'bg-secondary'
    ];
    
    return $statusClasses[$status] ?? 'bg-secondary';
}

// Calculate discounted price
function calculateDiscountedPrice($price, $discount) {
    return $price * (1 - $discount / 100);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawShop Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0099ff;
            --primary-dark: #0077cc;
            --secondary-color: #f8f9fa;
            --accent-color: #ffcc00;
            --text-color: #212529;
            --light-text: #6c757d;
            --white: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --border-radius: 10px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: #f5f8fa;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            position: fixed;
            height: 100vh;
            transition: var(--transition);
            z-index: 1000;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            transition: var(--transition);
            padding: 20px;
        }

        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-brand img {
            margin-right: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: var(--transition);
            border-left: 4px solid transparent;
        }

        .sidebar-item:hover, .sidebar-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 4px solid white;
        }

        .sidebar-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            .sidebar .sidebar-text {
                display: none;
            }
            .main-content {
                margin-left: 70px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.show {
                width: 250px;
            }
            .sidebar.show .sidebar-text {
                display: inline;
            }
            .filter-bar .row {
                flex-direction: column;
            }
            .filter-bar .col-md-3 {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
       <!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <a href="home.php" class="sidebar-brand">
            <i class="fas fa-paw me-2"></i>
            <span class="sidebar-text">PawShop</span>
        </a>
    </div>
    
    <div class="sidebar-menu">
        <a href="dashboard.php" class="sidebar-item">
            <i class="fas fa-chart-line"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>
        
        <a href="orders.php" class="sidebar-item">
            <i class="fas fa-shopping-bag"></i>
            <span class="sidebar-text">Orders</span>
        </a>
        
        <a href="inventory.php" class="sidebar-item active">
            <i class="fas fa-box-open"></i>
            <span class="sidebar-text">Inventory</span>
        </a>
        
        <a href="analytics.php" class="sidebar-item">
            <i class="fas fa-chart-bar"></i>
            <span class="sidebar-text">Sales Analytics</span>
        </a>
        
        <a href="promocodes.php" class="sidebar-item">
            <i class="fas fa-tag"></i>
            <span class="sidebar-text">Promo Codes</span>
        </a>
        
        <a href="logout.php" class="sidebar-item">
            <i class="fas fa-sign-out-alt"></i>
            <span class="sidebar-text">Logout</span>
        </a>
    </div>
</aside>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid px-4">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-primary mb-1">Product Inventory</h2>
                        <p class="text-muted">Manage your product inventory and stock levels</p>
                    </div>
                    <button class="btn btn-secondary px-4 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus me-2"></i> Add New Product
                    </button>
                </div>

                <!-- Filters and Actions Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search products...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    <option value="Food">Food</option>
                                    <option value="Toys">Toys</option>
                                    <option value="Accessories">Accessories</option>
                                    <option value="Health">Health & Wellness</option>
                                    <option value="Grooming">Grooming</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Low Stock">Low Stock</option>
                                    <option value="Out of Stock">Out of Stock</option>
                                    <option value="Discontinued">Discontinued</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="bulkActionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Bulk Actions
                                    </button>
                                    <ul class="dropdown-menu w-100" aria-labelledby="bulkActionDropdown">
                                        <li><a class="dropdown-item" href="#" id="applyDiscount"><i class="fas fa-tag me-2"></i> Apply Discount</a></li>
                                        <li><a class="dropdown-item" href="#" id="updateStock"><i class="fas fa-boxes me-2"></i> Update Stock</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" id="deleteSelected"><i class="fas fa-trash me-2"></i> Delete Selected</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Table Card -->
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="40">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th width="80">Image</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>SKU</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Status</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($products as $product): ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input product-select" type="checkbox" value="<?php echo $product['id']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-img-container bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-paw text-primary"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium"><?php echo htmlspecialchars($product['name']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($product['sku']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                                        <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                        <td>
                                            <?php if($product['stock'] > 20): ?>
                                                <span class="fw-medium"><?php echo $product['stock']; ?></span>
                                            <?php elseif($product['stock'] > 0): ?>
                                                <span class="fw-medium text-warning"><?php echo $product['stock']; ?></span>
                                            <?php else: ?>
                                                <span class="fw-medium text-danger"><?php echo $product['stock']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-medium"><?php echo formatPrice($product['price']); ?></div>
                                            <?php if($product['discount'] > 0): ?>
                                                <small class="text-success">
                                                    <?php echo formatPrice(calculateDiscountedPrice($product['price'], $product['discount'])); ?> after discount
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($product['discount'] > 0): ?>
                                                <span class="badge bg-success"><?php echo $product['discount']; ?>% OFF</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted">No Discount</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo getStatusClass($product['status']); ?>"><?php echo $product['status']; ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProductModal" data-product-id="<?php echo $product['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewProductModal" data-product-id="<?php echo $product['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#discountModal" data-product-id="<?php echo $product['id']; ?>">
                                                            <i class="fas fa-tag me-2 text-primary"></i> Manage Discount
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#stockModal" data-product-id="<?php echo $product['id']; ?>">
                                                            <i class="fas fa-boxes me-2 text-success"></i> Update Stock
                                                        </a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#">
                                                            <i class="fas fa-trash me-2"></i> Delete
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing <span class="fw-medium">1-<?php echo count($products); ?></span> of <span class="fw-medium">24</span> products
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="productName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="productName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="productCategory" class="form-label">Category</label>
                                <select class="form-select" id="productCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="Food">Food</option>
                                    <option value="Toys">Toys</option>
                                    <option value="Accessories">Accessories</option>
                                    <option value="Health">Health & Wellness</option>
                                    <option value="Grooming">Grooming</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="productSKU" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="productSKU" required>
                            </div>
                            <div class="col-md-4">
                                <label for="productPrice" class="form-label">Price (₱)</label>
                                <input type="number" class="form-control" id="productPrice" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="productStock" class="form-label">Initial Stock</label>
                                <input type="number" class="form-control" id="productStock" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="productDiscount" class="form-label">Discount (%)</label>
                                <input type="number" class="form-control" id="productDiscount" min="0" max="100" value="0">
                            </div>
                            <div class="col-md-6">
                                <label for="productStatus" class="form-label">Status</label>
                                <select class="form-select" id="productStatus" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Discontinued">Discontinued</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="productDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="productDescription" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label for="productImage" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="productImage">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProductBtn">Add Product</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Same form fields as Add Product Modal but with pre-filled values -->
                    <form id="editProductForm">
                        <div class="row g-3">
                            <!-- These would be pre-filled based on the product being edited -->
                            <div class="col-md-6">
                                <label for="editProductName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="editProductName" value="Premium Dog Food" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editProductCategory" class="form-label">Category</label>
                                <select class="form-select" id="editProductCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="Food" selected>Food</option>
                                    <option value="Toys">Toys</option>
                                    <option value="Accessories">Accessories</option>
                                    <option value="Health">Health & Wellness</option>
                                    <option value="Grooming">Grooming</option>
                                </select>
                            </div>
                            <!-- Additional fields similar to Add Product modal -->
                            <div class="col-md-4">
                                <label for="editProductSKU" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="editProductSKU" value="PF-001" required>
                            </div>
                            <div class="col-md-4">
                                <label for="editProductPrice" class="form-label">Price (₱)</label>
                                <input type="number" class="form-control" id="editProductPrice" value="2500" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="editProductStock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="editProductStock" value="1000" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editProductDiscount" class="form-label">Discount (%)</label>
                                <input type="number" class="form-control" id="editProductDiscount" value="15" min="0" max="100">
                            </div>
                            <div class="col-md-6">
                                <label for="editProductStatus" class="form-label">Status</label>
                                <select class="form-select" id="editProductStatus" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Discontinued">Discontinued</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="editProductDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editProductDescription" rows="3">High-quality premium dog food with balanced nutrition.</textarea>
                            </div>
                            <div class="col-12">
                                <label for="editProductImage" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="editProductImage">
                                <div class="mt-2">
                                    <span class="badge bg-light text-dark">Current image: dog-food.jpg</span>
                                </div>
                            </div>
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

    <!-- Discount Modal -->
    <div class="modal fade" id="discountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Manage Discount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="discountForm">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Product:</label>
                            <div id="discountProductName" class="fw-bold">Premium Dog Food</div>
                        </div>
                        <div class="mb-3">
                            <label for="discountType" class="form-label">Discount Type</label>
                            <select class="form-select" id="discountType">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (₱)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="discountValue" class="form-label">Discount Value</label>
                            <input type="number" class="form-control" id="discountValue" value="15" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="discountStartDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="discountStartDate">
                        </div>
                        <div class="mb-3">
                            <label for="discountEndDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="discountEndDate">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="removeDiscountBtn">Remove Discount</button>
                    <button type="button" class="btn btn-primary" id="applyDiscountBtn">Apply Discount</button>
                </div>
            </div>
        </div>
    </div>

   <!-- Stock Update Modal -->
<div class="modal fade" id="stockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Update Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="stockForm">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Product:</label>
                        <div id="stockProductName" class="fw-bold">Premium Dog Food</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Stock:</label>
                        <div id="currentStock" class="fw-bold">1000 units</div>
                    </div>
                    <div class="mb-3">
                        <label for="stockAction" class="form-label">Action</label>
                        <select class="form-select" id="stockAction">
                            <option value="add">Add Stock</option>
                            <option value="remove">Remove Stock</option>
                            <option value="set">Set Exact Stock</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="stockQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="stockQuantity" value="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="stockNote" class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="stockNote" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateStockBtn">Update Stock</button>
            </div>
        </div>
    </div>
</div>

<!-- View Product Modal -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-paw fa-3x text-primary"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-1">Premium Dog Food</h4>
                        <span class="badge bg-success mb-2">Active</span>
                        <p class="text-muted">High-quality premium dog food with balanced nutrition.</p>
                        
                        <div class="row mt-3">
                            <div class="col-6 mb-2">
                                <div class="text-muted small">Category</div>
                                <div class="fw-medium">Food</div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="text-muted small">SKU</div>
                                <div class="fw-medium">PF-001</div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="text-muted small">Price</div>
                                <div class="fw-medium">₱2,500.00</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Stock</div>
                                <div class="fw-medium">1000 units</div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="text-muted small">Discount</div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">15% OFF</span>
                                <span class="text-success">Final price: ₱2,125.00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">Stock History</h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-medium">+200 units added</div>
                                            <small class="text-muted">April 5, 2025</small>
                                        </div>
                                        <span class="badge bg-success rounded-pill">1000</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-medium">+300 units added</div>
                                            <small class="text-muted">March 22, 2025</small>
                                        </div>
                                        <span class="badge bg-success rounded-pill">800</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-medium">Initial stock</div>
                                            <small class="text-muted">March 10, 2025</small>
                                        </div>
                                        <span class="badge bg-success rounded-pill">500</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">Price & Discount History</h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-medium">15% discount applied</div>
                                            <small class="text-muted">April 1, 2025</small>
                                        </div>
                                        <span class="text-success">₱2,125.00</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-medium">Price increased</div>
                                            <small class="text-muted">March 15, 2025</small>
                                        </div>
                                        <span>₱2,500.00</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                        <div class="fw-medium">Initial price</div>
                                            <small class="text-muted">March 10, 2025</small>
                                        </div>
                                        <span>₱2,400.00</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal" data-product-id="1">Edit Product</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Apply Discount Modal -->
<div class="modal fade" id="bulkDiscountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Apply Bulk Discount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bulkDiscountForm">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Selected Products:</label>
                        <div id="selectedProductsCount" class="alert alert-info mb-3">
                            3 products selected
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="bulkDiscountType" class="form-label">Discount Type</label>
                        <select class="form-select" id="bulkDiscountType">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (₱)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bulkDiscountValue" class="form-label">Discount Value</label>
                        <input type="number" class="form-control" id="bulkDiscountValue" value="10" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="bulkDiscountStartDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="bulkDiscountStartDate">
                    </div>
                    <div class="mb-3">
                        <label for="bulkDiscountEndDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="bulkDiscountEndDate">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="applyBulkDiscountBtn">Apply to Selected</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Update Stock Modal -->
<div class="modal fade" id="bulkStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Bulk Update Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bulkStockForm">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Selected Products:</label>
                        <div id="bulkStockSelectedProducts" class="alert alert-info mb-3">
                            3 products selected
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="bulkStockAction" class="form-label">Action</label>
                        <select class="form-select" id="bulkStockAction">
                            <option value="add">Add Stock</option>
                            <option value="remove">Remove Stock</option>
                            <option value="percentage">Adjust by Percentage</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bulkStockQuantity" class="form-label">Quantity / Percentage</label>
                        <input type="number" class="form-control" id="bulkStockQuantity" value="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="bulkStockNote" class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="bulkStockNote" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateBulkStockBtn">Update Stock</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the selected product(s)? This action cannot be undone.</p>
                <div id="deleteProductCount" class="alert alert-warning mb-0">
                    1 product will be deleted
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        const productCheckboxes = document.querySelectorAll('.product-select');
        
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        
        // Check if all checkboxes are checked
        function updateSelectAllCheckbox() {
            let allChecked = true;
            let anyChecked = false;
            
            productCheckboxes.forEach(checkbox => {
                if (!checkbox.checked) allChecked = false;
                if (checkbox.checked) anyChecked = true;
            });
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = !allChecked && anyChecked;
        }
        
        productCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllCheckbox);
        });
        
        // Bulk action handlers
        document.getElementById('applyDiscount').addEventListener('click', function(e) {
            e.preventDefault();
            $('#bulkDiscountModal').modal('show');
        });
        
        document.getElementById('updateStock').addEventListener('click', function(e) {
            e.preventDefault();
            $('#bulkStockModal').modal('show');
        });
        
        document.getElementById('deleteSelected').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Count selected items
            let selectedCount = 0;
            productCheckboxes.forEach(checkbox => {
                if (checkbox.checked) selectedCount++;
            });
            
            if (selectedCount > 0) {
                document.getElementById('deleteProductCount').textContent = 
                    `${selectedCount} product${selectedCount > 1 ? 's' : ''} will be deleted`;
                $('#deleteConfirmModal').modal('show');
            } else {
                alert('Please select at least one product to delete');
            }
        });
        
        // Form submission handlers (these would normally submit to a backend)
        document.getElementById('saveProductBtn').addEventListener('click', function() {
            // Validate form
            const form = document.getElementById('addProductForm');
            if (form.checkValidity()) {
                alert('Product would be saved in a real application');
                $('#addProductModal').modal('hide');
                // Here you would normally submit the form or use AJAX
            } else {
                form.reportValidity();
            }
        });
        
        document.getElementById('updateProductBtn').addEventListener('click', function() {
            // Validate form
            const form = document.getElementById('editProductForm');
            if (form.checkValidity()) {
                alert('Product would be updated in a real application');
                $('#editProductModal').modal('hide');
                // Here you would normally submit the form or use AJAX
            } else {
                form.reportValidity();
            }
        });
        
        document.getElementById('applyDiscountBtn').addEventListener('click', function() {
            alert('Discount would be applied in a real application');
            $('#discountModal').modal('hide');
        });
        
        document.getElementById('removeDiscountBtn').addEventListener('click', function() {
            alert('Discount would be removed in a real application');
            $('#discountModal').modal('hide');
        });
        
        document.getElementById('updateStockBtn').addEventListener('click', function() {
            alert('Stock would be updated in a real application');
            $('#stockModal').modal('hide');
        });
        
        document.getElementById('applyBulkDiscountBtn').addEventListener('click', function() {
            alert('Bulk discount would be applied in a real application');
            $('#bulkDiscountModal').modal('hide');
        });
        
        document.getElementById('updateBulkStockBtn').addEventListener('click', function() {
            alert('Bulk stock update would be applied in a real application');
            $('#bulkStockModal').modal('hide');
        });
        
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            alert('Selected products would be deleted in a real application');
            $('#deleteConfirmModal').modal('hide');
        });
        
        // Handle product-specific modals
        const discountModal = document.getElementById('discountModal');
        discountModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            // In a real app, you would fetch product data and populate the modal
            // For now, we'll use static data
            document.getElementById('discountProductName').textContent = 
                productId == 1 ? 'Premium Dog Food' : 'Product ' + productId;
        });
        
        const stockModal = document.getElementById('stockModal');
        stockModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            // In a real app, you would fetch product data and populate the modal
            document.getElementById('stockProductName').textContent = 
                productId == 1 ? 'Premium Dog Food' : 'Product ' + productId;
            document.getElementById('currentStock').textContent = 
                productId == 1 ? '1000 units' : '100 units';
        });

        // Search functionality
    document.getElementById('searchroducts').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            const productText = row.querySelector('td:first-child').textContent.toLowerCase();
            const descText = row.querySelector('td:first-child .small').textContent.toLowerCase();
            
            if (promoText.includes(searchText) || descText.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>