<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Analytics</title>
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

        /* Order Dashboard Specific Styles */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-bar {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .orders-table {
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: var(--light-text);
        }

        .table td, .table th {
            padding: 15px;
            vertical-align: middle;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }

        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status-shipped {
            background-color: #17a2b8;
            color: white;
        }

        .status-delivered {
            background-color: #28a745;
            color: white;
        }

        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }

        .action-btn {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-right: 5px;
            transition: var(--transition);
        }

        .view-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .view-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .update-btn {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .update-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: white;
            border-top: 1px solid #e9ecef;
        }

        .page-link {
            padding: 5px 10px;
            margin: 0 3px;
            border-radius: 5px;
        }

        .page-link.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 15px 20px;
        }

        .order-details-section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .order-details-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: var(--light-text);
        }

        .action-modal-btn {
            border-radius: 20px;
            padding: 8px 15px;
            margin-right: 10px;
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
    <body>
    <div class="admin-container">
        <!-- Sidebar -->
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
        
        <a href="home.php" class="sidebar-item">
            <i class="fas fa-sign-out-alt"></i>
            <span class="sidebar-text">Logout</span>
        </a>
    </div>
</aside>

<div class="main-content">
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Sales Analytics</h2>
            <p class="text-muted">Comprehensive overview of your store's performance</p>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="timeRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-calendar-alt me-1"></i> This Month
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="timeRangeDropdown">
                    <li><a class="dropdown-item" href="#" data-value="today">Today</a></li>
                    <li><a class="dropdown-item" href="#" data-value="yesterday">Yesterday</a></li>
                    <li><a class="dropdown-item" href="#" data-value="last_7_days">Last 7 Days</a></li>
                    <li><a class="dropdown-item active" href="#" data-value="this_month">This Month</a></li>
                    <li><a class="dropdown-item" href="#" data-value="last_month">Last Month</a></li>
                    <li><a class="dropdown-item" href="#" data-value="this_year">This Year</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-value="custom" id="customRangeBtn">Custom Range</a></li>
                </ul>
            </div>
            <button class="btn btn-primary" id="refreshBtn">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Date Range Picker (Hidden by default) -->
    <div class="card mb-4 d-none" id="dateRangePicker">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label for="startDate" class="col-form-label">From:</label>
                </div>
                <div class="col-auto">
                    <input type="date" id="startDate" class="form-control form-control-sm">
                </div>
                <div class="col-auto">
                    <label for="endDate" class="col-form-label">To:</label>
                </div>
                <div class="col-auto">
                    <input type="date" id="endDate" class="form-control form-control-sm">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-primary" id="applyDateRange">Apply</button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-secondary" id="cancelDateRange">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Total Revenue</p>
                            <h3 class="fw-bold mb-0" id="totalRevenue">₱125,680</h3>
                            <div class="mt-2 text-success small" id="revenueChange">
                                <i class="fas fa-arrow-up me-1"></i>8.2% from last month
                            </div>
                        </div>
                        <div class="icon-shape bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="fas fa-peso-sign fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Total Orders</p>
                            <h3 class="fw-bold mb-0" id="totalOrders">300,680</h3>
                            <div class="mt-2 text-success small" id="ordersChange">
                                <i class="fas fa-arrow-up me-1"></i>12.5% from last month
                            </div>
                        </div>
                        <div class="icon-shape bg-success-subtle text-success rounded-3 p-3">
                            <i class="fas fa-shopping-cart fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Average Order Value</p>
                            <h3 class="fw-bold mb-0" id="averageOrderValue">₱1,280</h3>
                            <div class="mt-2 text-danger small" id="aovChange">
                                <i class="fas fa-arrow-down me-1"></i>3.1% from last month
                            </div>
                        </div>
                        <div class="icon-shape bg-info-subtle text-info rounded-3 p-3">
                            <i class="fas fa-receipt fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Abandoned Carts</p>
                            <h3 class="fw-bold mb-0" id="abandonedCarts">248</h3>
                            <div class="mt-2 text-warning small" id="cartRate">
                                <i class="fas fa-exclamation-triangle me-1"></i>24.8% cart abandonment rate
                            </div>
                        </div>
                        <div class="icon-shape bg-warning-subtle text-warning rounded-3 p-3">
                            <i class="fas fa-shopping-basket fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner (Hidden by default) -->
    <div class="text-center py-5 d-none" id="loadingIndicator">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading your analytics data...</p>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4" id="chartsRow">
        <!-- Sales Trends Chart -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sales Trends</h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary active" data-chart-view="daily">Daily</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-chart-view="weekly">Weekly</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-chart-view="monthly">Monthly</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesTrendsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Best Selling Products -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Best Selling Products</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="productTimeRange" data-bs-toggle="dropdown" aria-expanded="false">
                            This Month
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="productTimeRange">
                            <li><a class="dropdown-item" href="#" data-value="today">Today</a></li>
                            <li><a class="dropdown-item" href="#" data-value="this_week">This Week</a></li>
                            <li><a class="dropdown-item active" href="#" data-value="this_month">This Month</a></li>
                            <li><a class="dropdown-item" href="#" data-value="this_year">This Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body" id="bestSellingProducts">
                    <?php
                    // Sample product data - in real implementation, this would come from database
                    $bestProducts = [
                        [
                            'name' => 'Premium Dog Food',
                            'icon' => 'box',
                            'color' => 'primary',
                            'units' => 124,
                            'value' => '₱20,340',
                            'percent' => 100
                        ],
                        [
                            'name' => 'Cat Litter Box',
                            'icon' => 'paw',
                            'color' => 'success',
                            'units' => 89,
                            'value' => '₱15,540',
                            'percent' => 76
                        ],
                        [
                            'name' => 'Dog Toy Bundle',
                            'icon' => 'bone',
                            'color' => 'info',
                            'units' => 62,
                            'value' => '₱9,340',
                            'percent' => 45
                        ],
                        [
                            'name' => 'Cat Scratching Post',
                            'icon' => 'feather',
                            'color' => 'warning',
                            'units' => 41,
                            'value' => '₱8,140',
                            'percent' => 40
                        ]
                    ];
                    
                    foreach ($bestProducts as $product): ?>
                        <div class="product-item mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="product-icon bg-<?php echo $product['color']; ?>-subtle text-<?php echo $product['color']; ?> rounded p-2 me-3">
                                        <i class="fas fa-<?php echo $product['icon']; ?>"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?php echo $product['name']; ?></h6>
                                        <small class="text-muted"><?php echo $product['units']; ?> units sold</small>
                                    </div>
                                </div>
                                <span class="fw-bold"><?php echo $product['value']; ?></span>
                            </div>
                            <div class="progress" style="height: 8px">
                                <div class="progress-bar bg-<?php echo $product['color']; ?>" role="progressbar" 
                                     style="width: <?php echo $product['percent']; ?>%" 
                                     aria-valuenow="<?php echo $product['percent']; ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Metrics Row -->
    <div class="row g-4" id="metricsRow">
        <!-- Top Performing Categories -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Top Categories</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoriesChart" height="260"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Sales by Channel -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Sales by Channel</h5>
                </div>
                <div class="card-body">
                    <canvas id="channelsChart" height="260"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="orders.php" class="btn btn-sm btn-link">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="recentOrders">
                        <?php
                        // Sample recent orders - in real implementation, this would come from database
                        $recentOrders = [
                            [
                                'id' => '#ORD-45623',
                                'product' => 'Premium Dog Food + 2 more items',
                                'status' => 'Delivered',
                                'statusClass' => 'bg-success',
                                'price' => '₱2,340'
                            ],
                            [
                                'id' => '#ORD-45612',
                                'product' => 'Cat Litter Box',
                                'status' => 'Processing',
                                'statusClass' => 'bg-warning',
                                'price' => '₱1,750'
                            ],
                            [
                                'id' => '#ORD-45607',
                                'product' => 'Dog Toy Bundle + 1 more item',
                                'status' => 'Shipped',
                                'statusClass' => 'bg-info',
                                'price' => '₱1,620'
                            ],
                            [
                                'id' => '#ORD-45598',
                                'product' => 'Cat Scratching Post',
                                'status' => 'Delivered',
                                'statusClass' => 'bg-success',
                                'price' => '₱1,900'
                            ],
                            [
                                'id' => '#ORD-45583',
                                'product' => 'Premium Dog Food',
                                'status' => 'Processing',
                                'statusClass' => 'bg-warning',
                                'price' => '₱950'
                            ]
                        ];
                        
                        foreach ($recentOrders as $order): ?>
                            <div class="list-group-item border-0 border-bottom py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo $order['id']; ?></h6>
                                        <p class="text-muted small mb-0"><?php echo $order['product']; ?></p>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge rounded-pill <?php echo $order['statusClass']; ?>"><?php echo $order['status']; ?></span>
                                        <p class="text-muted small mb-0 mt-1"><?php echo $order['price']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="analyticsToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i> Data updated successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Include footer/scripts -->
<?php include_once('includes/footer.php'); ?>

<!-- Chart.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Initialize all charts and data
    let salesTrendsChart, categoriesChart, channelsChart;
    let currentTimeRange = 'this_month';
    let currentProductTimeRange = 'this_month';
    let currentChartView = 'daily';
    
    // Mock data for different time ranges
    const analyticsData = {
        'today': {
            kpis: {
                totalRevenue: '₱12,580',
                revenueChange: '<i class="fas fa-arrow-up me-1"></i>5.2% from yesterday',
                totalOrders: '30,120',
                ordersChange: '<i class="fas fa-arrow-up me-1"></i>8.3% from yesterday',
                averageOrderValue: '₱1,150',
                aovChange: '<i class="fas fa-arrow-down me-1"></i>2.1% from yesterday',
                abandonedCarts: '42',
                cartRate: '<i class="fas fa-exclamation-triangle me-1"></i>21.5% cart abandonment rate'
            },
            salesTrends: {
                labels: ['6AM', '9AM', '12PM', '3PM', '6PM', '9PM', '12AM'],
                revenue: [1200, 1800, 2400, 2100, 3200, 2800, 1500],
                orders: [25, 40, 65, 50, 70, 60, 30]
            },
            categories: [40, 20, 25, 10, 5],
            channels: [45, 30, 15, 7, 3]
        },
        'yesterday': {
            kpis: {
                totalRevenue: '₱11,950',
                revenueChange: '<i class="fas fa-arrow-down me-1"></i>3.1% from previous day',
                totalOrders: '27,815',
                ordersChange: '<i class="fas fa-arrow-down me-1"></i>2.5% from previous day',
                averageOrderValue: '₱1,175',
                aovChange: '<i class="fas fa-arrow-up me-1"></i>1.2% from previous day',
                abandonedCarts: '39',
                cartRate: '<i class="fas fa-exclamation-triangle me-1"></i>22.1% cart abandonment rate'
            },
            salesTrends: {
                labels: ['6AM', '9AM', '12PM', '3PM', '6PM', '9PM', '12AM'],
                revenue: [1100, 1700, 2200, 2000, 3000, 2600, 1400],
                orders: [22, 38, 60, 48, 65, 55, 28]
            },
            categories: [38, 22, 24, 11, 5],
            channels: [44, 31, 14, 8, 3]
        },
        'last_7_days': {
            kpis: {
                totalRevenue: '₱72,450',
                revenueChange: '<i class="fas fa-arrow-up me-1"></i>7.5% from previous week',
                totalOrders: '180,320',
                ordersChange: '<i class="fas fa-arrow-up me-1"></i>9.7% from previous week',
                averageOrderValue: '₱1,210',
                aovChange: '<i class="fas fa-arrow-up me-1"></i>2.3% from previous week',
                abandonedCarts: '128',
                cartRate: '<i class="fas fa-exclamation-triangle me-1"></i>23.4% cart abandonment rate'
            },
            salesTrends: {
                labels: ['Apr 3', 'Apr 4', 'Apr 5', 'Apr 6', 'Apr 7', 'Apr 8', 'Apr 9'],
                revenue: [9800, 10200, 9500, 11300, 10800, 12500, 8350],
                orders: [245, 255, 230, 280, 270, 300, 210]
            },
            categories: [36, 24, 22, 12, 6],
            channels: [42, 32, 15, 8, 3]
        },
        'this_month': {
            kpis: {
                totalRevenue: '₱125,680',
                revenueChange: '<i class="fas fa-arrow-up me-1"></i>8.2% from last month',
                totalOrders: '300,680',
                ordersChange: '<i class="fas fa-arrow-up me-1"></i>12.5% from last month',
                averageOrderValue: '₱1,280',
                aovChange: '<i class="fas fa-arrow-down me-1"></i>3.1% from last month',
                abandonedCarts: '248',
                cartRate: '<i class="fas fa-exclamation-triangle me-1"></i>24.8% cart abandonment rate'
            },
            salesTrends: {
                labels: ['Apr 1', 'Apr 5', 'Apr 10', 'Apr 15', 'Apr 20', 'Apr 25', 'Apr 30'],
                revenue: [4500, 5200, 4800, 6000, 5700, 7200, 6800],
                orders: [120, 150, 135, 175, 160, 190, 180]
            },
            categories: [35, 25, 20, 15, 5],
            channels: [42, 28, 18, 8, 4]
        },
        'last_month': {
            kpis: {
                totalRevenue: '₱116,140',
                revenueChange: '<i class="fas fa-arrow-up me-1"></i>5.1% from previous month',
                totalOrders: '267,290',
                ordersChange: '<i class="fas fa-arrow-up me-1"></i>4.3% from previous month',
                averageOrderValue: '₱1,320',
                aovChange: '<i class="fas fa-arrow-up me-1"></i>2.8% from previous month',
                abandonedCarts: '230',
                cartRate: '<i class="fas fa-exclamation-triangle me-1"></i>25.2% cart abandonment rate'
            },
            salesTrends: {
                labels: ['Mar 1', 'Mar 5', 'Mar 10', 'Mar 15', 'Mar 20', 'Mar 25', 'Mar 31'],
                revenue: [4200, 4800, 4500, 5500, 5200, 6800, 6300],
                orders: [110, 140, 130, 160, 150, 175, 165]
            },
            categories: [33, 27, 19, 16, 5],
            channels: [40, 29, 19, 8, 4]
        },
        'this_year': {
            kpis: {
                totalRevenue: '₱458,750',
                revenueChange: '<i class="fas fa-arrow-up me-1"></i>12.7% from last year',
                totalOrders: '1,250,430',
                ordersChange: '<i class="fas fa-arrow-up me-1"></i>15.2% from last year',
                averageOrderValue: '₱1,250',
                aovChange: '<i class="fas fa-arrow-up me-1"></i>4.5% from last year',
                abandonedCarts: '980',
                cartRate: '<i class="fas fa-exclamation-triangle me-1"></i>23.7% cart abandonment rate'
            },
            salesTrends: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                revenue: [38000, 42000, 39000, 45000, null, null, null, null, null, null, null, null],
                orders: [950, 1050, 980, 1120, null, null, null, null, null, null, null, null]
            },
            categories: [32, 26, 22, 14, 6],
            channels: [38, 30, 20, 8, 4]
        }
    };
    
    // Best selling products data by time range
      const productsData = {
        'today': [
            { name: 'Premium Dog Food', icon: 'box', color: 'primary', units: 14, value: '₱2,940', percent: 100 },
            { name: 'Cat Litter Box', icon: 'paw', color: 'success', units: 10, value: '₱1,750', percent: 60 },
            { name: 'Dog Toy Bundle', icon: 'bone', color: 'info', units: 8, value: '₱1,200', percent: 41 },
            { name: 'Cat Scratching Post', icon: 'feather', color: 'warning', units: 6, value: '₱1,140', percent: 39 }
        ],
        'this_week': [
            { name: 'Premium Dog Food', icon: 'box', color: 'primary', units: 48, value: '₱9,840', percent: 100 },
            { name: 'Cat Litter Box', icon: 'paw', color: 'success', units: 36, value: '₱6,300', percent: 64 },
            { name: 'Dog Toy Bundle', icon: 'bone', color: 'info', units: 29, value: '₱4,350', percent: 44 },
            { name: 'Cat Scratching Post', icon: 'feather', color: 'warning', units: 22, value: '₱4,180', percent: 42 }
        ],
        'this_month': [
            { name: 'Premium Dog Food', icon: 'box', color: 'primary', units: 124, value: '₱20,340', percent: 100 },
            { name: 'Cat Litter Box', icon: 'paw', color: 'success', units: 89, value: '₱15,540', percent: 76 },
            { name: 'Dog Toy Bundle', icon: 'bone', color: 'info', units: 62, value: '₱9,340', percent: 45 },
            { name: 'Cat Scratching Post', icon: 'feather', color: 'warning', units: 41, value: '₱8,140', percent: 40 }
        ],
        'this_year': [
            { name: 'Premium Dog Food', icon: 'box', color: 'primary', units: 1248, value: '₱187,200', percent: 100 },
            { name: 'Cat Litter Box', icon: 'paw', color: 'success', units: 943, value: '₱164,780', percent: 88 },
            { name: 'Dog Toy Bundle', icon: 'bone', color: 'info', units: 687, value: '₱103,050', percent: 55 },
            { name: 'Cat Scratching Post', icon: 'feather', color: 'warning', units: 512, value: '₱97,280', percent: 52 }
        ]
    };
    
    // Recent orders data
    const recentOrdersData = [
        { id: '#ORD-45623', product: 'Premium Dog Food + 2 more items', status: 'Delivered', statusClass: 'bg-success', price: '₱2,340' },
        { id: '#ORD-45612', product: 'Cat Litter Box', status: 'Processing', statusClass: 'bg-warning', price: '₱1,750' },
        { id: '#ORD-45607', product: 'Dog Toy Bundle + 1 more item', status: 'Shipped', statusClass: 'bg-info', price: '₱1,620' },
        { id: '#ORD-45598', product: 'Cat Scratching Post', status: 'Delivered', statusClass: 'bg-success', price: '₱1,900' },
        { id: '#ORD-45583', product: 'Premium Dog Food', status: 'Processing', statusClass: 'bg-warning', price: '₱950' }
    ];
    
    // Chart view data (daily, weekly, monthly)
    const chartViewData = {
        'daily': {
            labels: ['Apr 1', 'Apr 2', 'Apr 3', 'Apr 4', 'Apr 5', 'Apr 6', 'Apr 7', 'Apr 8', 'Apr 9'],
            revenue: [4200, 4500, 4300, 4800, 4600, 5100, 4900, 5300, 5000],
            orders: [105, 112, 108, 120, 115, 128, 122, 132, 125]
        },
        'weekly': {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            revenue: [21400, 25800, 28600, 31500],
            orders: [535, 645, 715, 790]
        },
        'monthly': {
            labels: ['Jan', 'Feb', 'Mar', 'Apr'],
            revenue: [96500, 105200, 116140, 125680],
            orders: [2410, 2630, 2905, 3140]
        }
    };
    
    // Initialize charts when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
        setupEventListeners();
        updateDashboardData(currentTimeRange);
    });
    
    // Initialize all chart instances
    function initCharts() {
        // Sales Trends Chart
        const salesTrendsCtx = document.getElementById('salesTrendsChart').getContext('2d');
        salesTrendsChart = new Chart(salesTrendsCtx, {
            type: 'line',
            data: {
                labels: analyticsData[currentTimeRange].salesTrends.labels,
                datasets: [
                    {
                        label: 'Revenue',
                        data: analyticsData[currentTimeRange].salesTrends.revenue,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#0d6efd',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Orders',
                        data: analyticsData[currentTimeRange].salesTrends.orders,
                        borderColor: '#20c997',
                        backgroundColor: 'rgba(32, 201, 151, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#20c997',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        cornerRadius: 4,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 0) {
                                    label += '₱' + context.raw.toLocaleString();
                                } else {
                                    label += context.raw.toLocaleString() + ' orders';
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 2]
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000) {
                                    return '₱' + (value / 1000).toFixed(0) + 'k';
                                }
                                return '₱' + value;
                            }
                        }
                    }
                }
            }
        });
        
        // Categories Chart (Doughnut)
        const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
        categoriesChart = new Chart(categoriesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Dog Food', 'Cat Food', 'Toys', 'Accessories', 'Other'],
                datasets: [{
                    data: analyticsData[currentTimeRange].categories,
                    backgroundColor: [
                        '#0d6efd',
                        '#20c997',
                        '#0dcaf0',
                        '#ffc107',
                        '#6c757d'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${percentage}% (${value}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Channels Chart (Pie)
        const channelsCtx = document.getElementById('channelsChart').getContext('2d');
        channelsChart = new Chart(channelsCtx, {
            type: 'pie',
            data: {
                labels: ['Direct Website', 'Marketplace', 'Social Media', 'Mobile App', 'Other'],
                datasets: [{
                    data: analyticsData[currentTimeRange].channels,
                    backgroundColor: [
                        '#6610f2',
                        '#fd7e14',
                        '#dc3545',
                        '#0dcaf0',
                        '#6c757d'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${percentage}%`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Set up all event listeners
    function setupEventListeners() {
        // Time range dropdown
        document.querySelectorAll('#timeRangeDropdown + .dropdown-menu .dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedRange = this.getAttribute('data-value');
                document.querySelector('#timeRangeDropdown').innerHTML = `<i class="fas fa-calendar-alt me-1"></i> ${this.textContent}`;
                
                // If custom range, show date picker
                if (selectedRange === 'custom') {
                    document.getElementById('dateRangePicker').classList.remove('d-none');
                } else {
                    document.getElementById('dateRangePicker').classList.add('d-none');
                    currentTimeRange = selectedRange;
                    updateDashboardData(currentTimeRange);
                }
                
                // Update active state
                document.querySelectorAll('#timeRangeDropdown + .dropdown-menu .dropdown-item').forEach(el => {
                    el.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
        
        // Products time range dropdown
        document.querySelectorAll('#productTimeRange + .dropdown-menu .dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedRange = this.getAttribute('data-value');
                document.getElementById('productTimeRange').textContent = this.textContent;
                currentProductTimeRange = selectedRange;
                updateBestSellingProducts(selectedRange);
                
                // Update active state
                document.querySelectorAll('#productTimeRange + .dropdown-menu .dropdown-item').forEach(el => {
                    el.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
        
        // Chart view buttons
        document.querySelectorAll('[data-chart-view]').forEach(button => {
            button.addEventListener('click', function() {
                const view = this.getAttribute('data-chart-view');
                currentChartView = view;
                
                // Update active state
                document.querySelectorAll('[data-chart-view]').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // Update chart data
                updateSalesTrendsChart(view);
            });
        });
        
        // Date range picker buttons
        document.getElementById('applyDateRange').addEventListener('click', function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            if (startDate && endDate) {
                // Format dates for display
                const start = new Date(startDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                const end = new Date(endDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                
                document.querySelector('#timeRangeDropdown').innerHTML = `<i class="fas fa-calendar-alt me-1"></i> ${start} - ${end}`;
                document.getElementById('dateRangePicker').classList.add('d-none');
                
                // For demo purposes, just load this_month data
                updateDashboardData('this_month');
                
                // Show toast message
                const toast = new bootstrap.Toast(document.getElementById('analyticsToast'));
                toast.show();
            }
        });
        
        document.getElementById('cancelDateRange').addEventListener('click', function() {
            document.getElementById('dateRangePicker').classList.add('d-none');
        });
        
        // Refresh button
        document.getElementById('refreshBtn').addEventListener('click', function() {
            // Show loading indicator
            document.getElementById('loadingIndicator').classList.remove('d-none');
            document.getElementById('chartsRow').classList.add('d-none');
            document.getElementById('metricsRow').classList.add('d-none');
            
            // Simulate loading delay
            setTimeout(function() {
                document.getElementById('loadingIndicator').classList.add('d-none');
                document.getElementById('chartsRow').classList.remove('d-none');
                document.getElementById('metricsRow').classList.remove('d-none');
                
                updateDashboardData(currentTimeRange);
                
                // Show toast message
                const toast = new bootstrap.Toast(document.getElementById('analyticsToast'));
                toast.show();
            }, 1500);
        });
    }
    
    // Update KPIs and charts based on selected time range
    function updateDashboardData(timeRange) {
        // Update KPIs
        const kpis = analyticsData[timeRange].kpis;
        document.getElementById('totalRevenue').textContent = kpis.totalRevenue;
        document.getElementById('revenueChange').innerHTML = kpis.revenueChange;
        document.getElementById('totalOrders').textContent = kpis.totalOrders;
        document.getElementById('ordersChange').innerHTML = kpis.ordersChange;
        document.getElementById('averageOrderValue').textContent = kpis.averageOrderValue;
        document.getElementById('aovChange').innerHTML = kpis.aovChange;
        document.getElementById('abandonedCarts').textContent = kpis.abandonedCarts;
        document.getElementById('cartRate').innerHTML = kpis.cartRate;
        
        // Update Charts
        updateSalesTrendsChart(currentChartView);
        updateCategoriesChart(timeRange);
        updateChannelsChart(timeRange);
        updateBestSellingProducts(currentProductTimeRange);
        updateRecentOrders();
    }
    
    // Update sales trends chart based on view (daily, weekly, monthly)
    function updateSalesTrendsChart(view) {
        salesTrendsChart.data.labels = chartViewData[view].labels;
        salesTrendsChart.data.datasets[0].data = chartViewData[view].revenue;
        salesTrendsChart.data.datasets[1].data = chartViewData[view].orders;
        salesTrendsChart.update();
    }
    
    // Update categories chart
    function updateCategoriesChart(timeRange) {
        categoriesChart.data.datasets[0].data = analyticsData[timeRange].categories;
        categoriesChart.update();
    }
    
    // Update channels chart
    function updateChannelsChart(timeRange) {
        channelsChart.data.datasets[0].data = analyticsData[timeRange].channels;
        channelsChart.update();
    }
    
    // Update best selling products
    function updateBestSellingProducts(timeRange) {
        const productsHTML = productsData[timeRange].map(product => `
            <div class="product-item mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <div class="product-icon bg-${product.color}-subtle text-${product.color} rounded p-2 me-3">
                            <i class="fas fa-${product.icon}"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">${product.name}</h6>
                            <small class="text-muted">${product.units} units sold</small>
                        </div>
                    </div>
                    <span class="fw-bold">${product.value}</span>
                </div>
                <div class="progress" style="height: 8px">
                    <div class="progress-bar bg-${product.color}" role="progressbar" 
                         style="width: ${product.percent}%" 
                         aria-valuenow="${product.percent}" 
                         aria-valuemin="0" 
                         aria-valuemax="100"></div>
                </div>
            </div>
        `).join('');
        
        document.getElementById('bestSellingProducts').innerHTML = productsHTML;
    }
    
    // Update recent orders
    function updateRecentOrders() {
        const ordersHTML = recentOrdersData.map(order => `
            <div class="list-group-item border-0 border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">${order.id}</h6>
                        <p class="text-muted small mb-0">${order.product}</p>
                    </div>
                    <div class="text-end">
                        <span class="badge rounded-pill ${order.statusClass}">${order.status}</span>
                        <p class="text-muted small mb-0 mt-1">${order.price}</p>
                    </div>
                </div>
            </div>
        `).join('');
        
        document.getElementById('recentOrders').innerHTML = ordersHTML;
    }
</script>
</body>
</html>