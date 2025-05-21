<?php
// Mock data for promo codes
$promoCodes = [
    [
        'code' => 'BBM2025',
        'description' => 'Black Friday Sale',
        'discount_type' => 'percentage',
        'discount_value' => '20',
        'usage' => 124,
        'limit' => 500,
        'expiration_date' => '2025-03-29',
        'status' => 'Active',
        'days_remaining' => 4
    ],
    [
        'code' => 'DDS2026',
        'description' => 'New User Discount',
        'discount_type' => 'percentage',
        'discount_value' => '10',
        'usage' => 85,
        'limit' => 300,
        'expiration_date' => '2025-03-29',
        'status' => 'Active',
        'days_remaining' => 4
    ],
    [
        'code' => 'LENILUGAW2030',
        'description' => 'Summer Collection',
        'discount_type' => 'percentage',
        'discount_value' => '15',
        'usage' => 42,
        'limit' => 200,
        'expiration_date' => '2025-03-29',
        'status' => 'Active',
        'days_remaining' => 4
    ],
    [
        'code' => 'HOLIDAY2025',
        'description' => 'Holiday Special',
        'discount_type' => 'percentage',
        'discount_value' => '25',
        'usage' => 0,
        'limit' => 100,
        'expiration_date' => '2025-12-25',
        'status' => 'Scheduled',
        'days_remaining' => 'months'
    ],
    [
        'code' => 'SPRING2024',
        'description' => 'Spring Collection',
        'discount_type' => 'percentage',
        'discount_value' => '18',
        'usage' => 321,
        'limit' => 400,
        'expiration_date' => '2025-03-15',
        'status' => 'Expired',
        'days_remaining' => 'Expired'
    ]
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a real application, you would validate and save the promo code
    // For demonstration, we'll just add it to our array
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $newPromo = [
            'code' => $_POST['code'],
            'description' => $_POST['description'] ?? '',
            'discount_type' => $_POST['discount_type'] ?? 'percentage',
            'discount_value' => $_POST['discount_value'] ?? 0,
            'usage' => 0,
            'limit' => $_POST['usage_limit'] ?? 0,
            'expiration_date' => $_POST['expiration_date'] ?? date('Y-m-d'),
            'status' => isset($_POST['is_active']) ? 'Active' : 'Inactive',
            'days_remaining' => 'New'
        ];
        
        // In a real app, you would save to database here
        
        // Redirect to prevent form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Helper function to get badge class based on status
function getBadgeClass($status) {
    switch ($status) {
        case 'Active':
            return 'bg-success-subtle text-success';
        case 'Scheduled':
            return 'bg-warning-subtle text-warning';
        case 'Expired':
            return 'bg-secondary-subtle text-secondary';
        default:
            return 'bg-light text-dark';
    }
}

// Helper function to get discount badge class
function getDiscountBadgeClass($value) {
    if ($value >= 25) {
        return 'bg-warning text-dark';
    } elseif ($value >= 20) {
        return 'bg-success text-white';
    } elseif ($value >= 15) {
        return 'bg-primary text-white';
    } elseif ($value >= 10) {
        return 'bg-info text-white';
    } else {
        return 'bg-secondary text-white';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo Codes</title>
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

        /* Promo Codes Page Specific Styles */
        .page-header {
            margin-bottom: 20px;
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .table th {
            font-weight: 600;
            color: var(--light-text);
        }

        .table td, .table th {
            padding: 15px;
            vertical-align: middle;
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
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-brand">
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
        
        <a href="home.php" class="sidebar-item">
            <i class="fas fa-sign-out-alt"></i>
            <span class="sidebar-text">Logout</span>
        </a>
    </div>
</aside>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid p-0">
                <!-- Page Header -->
                <div class="page-header mb-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="page-title">Promo Codes</h1>
                            <p class="text-muted">Manage discount codes for your store</p>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPromoModal">
                                <i class="fas fa-plus-circle me-2"></i>Create New Promo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Promo Codes List Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">Active Promo Codes</h5>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search promo codes..." id="searchPromo">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Promo Code</th>
                                        <th>Discount</th>
                                        <th>Usage / Limit</th>
                                        <th>Expiration Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($promoCodes as $promo): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-medium <?php echo $promo['status'] === 'Expired' ? 'text-decoration-line-through' : ''; ?>">
                                                <?php echo htmlspecialchars($promo['code']); ?>
                                            </span>
                                            <div class="small text-muted"><?php echo htmlspecialchars($promo['description']); ?></div>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo getDiscountBadgeClass($promo['discount_value']); ?>">
                                                <?php echo htmlspecialchars($promo['discount_value']); ?>% OFF
                                            </span>
                                        </td>
                                        <td><?php echo $promo['usage']; ?> / <?php echo $promo['limit']; ?></td>
                                        <td>
                                            <div><?php echo date('d-m-Y', strtotime($promo['expiration_date'])); ?></div>
                                            <div class="small text-muted">
                                                <?php 
                                                if (is_numeric($promo['days_remaining'])) {
                                                    echo $promo['days_remaining'] . ' days remaining'; 
                                                } else {
                                                    echo $promo['days_remaining']; 
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo getBadgeClass($promo['status']); ?>">
                                                <?php echo $promo['status']; ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-chart-line me-2"></i>View Stats</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash-alt me-2"></i>Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-3">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Promo Modal -->
    <div class="modal fade" id="createPromoModal" tabindex="-1" aria-labelledby="createPromoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPromoModalLabel">Create New Promo Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="promoCode" class="form-label">Promo Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="promoCode" name="code" placeholder="Enter promo code" required>
                                <button class="btn btn-outline-secondary" type="button" id="generateCode">
                                    <i class="fas fa-sync-alt"></i> Generate
                                </button>
                            </div>
                            <div class="form-text">Unique code customers will enter at checkout</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="promoDescription" class="form-label">Description</label>
                            <input type="text" class="form-control" id="promoDescription" name="description" placeholder="e.g., Summer Sale">
                            <div class="form-text">Brief description for your reference</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="discountType" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="discountType" name="discount_type" required>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount ($)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="discountValue" class="form-label">Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="discountValue" name="discount_value" min="1" max="100" placeholder="e.g., 20" required>
                                    <span class="input-group-text" id="discountSymbol">%</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date">
                            </div>
                            <div class="col-md-6">
                                <label for="expirationDate" class="form-label">Expiration Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="expirationDate" name="expiration_date" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="usageLimit" class="form-label">Usage Limit</label>
                                <input type="number" class="form-control" id="usageLimit" name="usage_limit" min="1" placeholder="e.g., 100">
                                <div class="form-text">Leave empty for unlimited use</div>
                            </div>
                            <div class="col-md-6">
                                <label for="minPurchase" class="form-label">Minimum Purchase</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="minPurchase" name="min_purchase" min="0" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="promoStatus" name="is_active" checked>
                            <label class="form-check-label" for="promoStatus">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Create Promo Code
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Generate random promo code
        document.getElementById('generateCode').addEventListener('click', function() {
            const characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
                result += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            document.getElementById('promoCode').value = result;
        });
        
        // Change discount symbol based on discount type
        document.getElementById('discountType').addEventListener('change', function() {
            const symbol = this.value === 'percentage' ? '%' : '$';
            document.getElementById('discountSymbol').textContent = symbol;
            
            const valueInput = document.getElementById('discountValue');
            if (this.value === 'percentage') {
                valueInput.setAttribute('max', '100');
            } else {
                valueInput.removeAttribute('max');
            }
        });
        
        // Set minimum date for expiration date field
        window.addEventListener('DOMContentLoaded', (event) => {
            const today = new Date();
            const formattedDate = today.toISOString().substr(0, 10);
            document.getElementById('startDate').setAttribute('min', formattedDate);
            document.getElementById('expirationDate').setAttribute('min', formattedDate);
            
            // Set default start date to today
            document.getElementById('startDate').value = formattedDate;
        });
        
        // Search functionality
        document.getElementById('searchPromo').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const promoText = row.querySelector('td:first-child').textContent.toLowerCase();
                
                if (promoText.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebar = function() {
                document.querySelector('.sidebar').classList.toggle('show');
            };
            
            // Add toggle button if on mobile (for demonstration)
            if (window.innerWidth <= 576) {
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'btn btn-sm btn-primary position-fixed';
                toggleBtn.style.top = '10px';
                toggleBtn.style.left = '10px';
                toggleBtn.style.zIndex = '1001';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.addEventListener('click', toggleSidebar);
                document.body.appendChild(toggleBtn);
            }
        });
    </script>
</body>
</html>
