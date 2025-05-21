<?php
// Pure PHP implementation of PawShop Dashboard
session_start();

// Simple route handling
$current_route = $_SERVER['REQUEST_URI'];

// Helper function to check if current route matches
function routeIs($route) {
    global $current_route;
    return strpos($current_route, $route) !== false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawShop Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Main Stylesheet for PawShop */
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

        /* Admin-specific Styles */
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
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            transition: var(--transition);
        }

        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
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

        /* Common Button Styles */
        .btn {
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Card Styles */
        .card {
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--box-shadow);
            transform: translateY(-3px);
        }

        /* Responsive */
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
                z-index: 1000;
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

        /* Custom navbar styles */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .navbar-toggler {
            border: none;
            padding: 0;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="dashboard.php" class="sidebar-brand">
                    <i class="fas fa-paw me-2"></i>
                    <span class="sidebar-text">PawShop</span>
                </a>
            </div>
            
            <div class="sidebar-menu">
                <a href="dashboard.php" class="sidebar-item <?php echo routeIs('dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                
                <a href="orders.php" class="sidebar-item <?php echo routeIs('orders') ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="sidebar-text">Orders</span>
                </a>
                
                <a href="inventory.php" class="sidebar-item <?php echo routeIs('inventory') ? 'active' : ''; ?>">
                    <i class="fas fa-box-open"></i>
                    <span class="sidebar-text">Inventory</span>
                </a>
                
                <a href="analytics.php" class="sidebar-item <?php echo routeIs('analytics') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span class="sidebar-text">Sales Analytics</span>
                </a>
                
                <a href="promocodes.php" class="sidebar-item <?php echo routeIs('promocodes') ? 'active' : ''; ?>">
                    <i class="fas fa-tag"></i>
                    <span class="sidebar-text">Promo Codes</span>
                </a>
                
                <a href="logout.php" class="sidebar-item" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-text">Logout</span>
                </a>
                
                <form id="logout-form" action="logout.php" method="POST" class="d-none">
                    <!-- CSRF token would go here in a real application -->
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i>
                                    Admin User
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Dashboard Content -->
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2 mb-0">Dashboard</h1>
                    <div class="text-end">
                        <span class="text-secondary me-2"><?php echo date('F d, Y'); ?></span>
                    </div>
                </div>

                <!-- Key Metrics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h6 class="text-secondary mb-3">Daily Sales</h6>
                                <h3 class="mb-0">₱ 150,000</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h6 class="text-secondary mb-3">New Orders</h6>
                                <h3 class="mb-0">25</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h6 class="text-secondary mb-3">Low Stock Items</h6>
                                <h3 class="mb-0">70</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <!-- Monthly Revenue Chart -->
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Monthly Revenue</h5>
                                    <div>
                                        <small class="text-secondary">This week vs website and compared with e-commerce stats</small>
                                    </div>
                                </div>
                                <div class="chart-container" style="height: 250px;">
                                    <canvas id="monthlyRevenueChart"></canvas>
                                </div>
                                <div class="mt-3 d-flex justify-content-end">
                                    <span class="me-4">
                                        <span class="badge bg-primary">&nbsp;</span>
                                        <small class="ms-1">Physical</small>
                                    </span>
                                    <span>
                                        <span class="badge bg-info">&nbsp;</span>
                                        <small class="ms-1">Digital</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales By Category -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Sales By Category</h5>
                                <div class="chart-container" style="height: 250px;">
                                    <canvas id="categoryChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Orders Table -->
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Recent Orders</h5>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Order ID</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#1001</td>
                                                <td>Juan Dela Cruz</td>
                                                <td>April 2, 2025</td>
                                                <td>₱ 3,500</td>
                                                <td><span class="badge bg-success">Completed</span></td>
                                                <td><button class="btn btn-sm btn-primary">View</button></td>
                                            </tr>
                                            <tr>
                                                <td>#1002</td>
                                                <td>Maria Santos</td>
                                                <td>April 2, 2025</td>
                                                <td>₱ 1,200</td>
                                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                                <td><button class="btn btn-sm btn-primary">View</button></td>
                                            </tr>
                                            <tr>
                                                <td>#1003</td>
                                                <td>Pedro Flores</td>
                                                <td>April 1, 2025</td>
                                                <td>₱ 7,800</td>
                                                <td><span class="badge bg-danger">Cancelled</span></td>
                                                <td><button class="btn btn-sm btn-primary">View</button></td>
                                            </tr>
                                            <tr>
                                                <td>#1004</td>
                                                <td>Ana Reyes</td>
                                                <td>April 1, 2025</td>
                                                <td>₱ 5,600</td>
                                                <td><span class="badge bg-success">Completed</span></td>
                                                <td><button class="btn btn-sm btn-primary">View</button></td>
                                            </tr>
                                            <tr>
                                                <td>#1005</td>
                                                <td>Maria Rai</td>
                                                <td>April 1, 2025</td>
                                                <td>₱ 980</td>
                                                <td><span class="badge bg-success">Delivered</span></td>
                                                <td><button class="btn btn-sm btn-primary">View</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Alerts -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Inventory Alerts</h5>
                                <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <div>Dog Food (Premium) - 2 items left</div>
                                </div>
                                <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>Cat Toys (Catnip Mouse) - 5 items left</div>
                                </div>
                                <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>Dog Leash (Medium) - 8 items left</div>
                                </div>
                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>Bird Cage (Small) - 10 items left</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Revenue Chart
            const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
            const monthlyRevenueChart = new Chart(monthlyRevenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [
                        {
                            label: 'Physical Store',
                            data: [150000, 175000, 140000, 180000, 200000, 160000, 130000],
                            backgroundColor: '#0099ff',
                            borderWidth: 0,
                            borderRadius: 5,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        },
                        {
                            label: 'Online Store',
                            data: [100000, 125000, 110000, 90000, 105000, 125000, 150000],
                            backgroundColor: '#90cdf4',
                            borderWidth: 0,
                            borderRadius: 5,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Category Sales Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryChart = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Dog Products', 'Cat Products', 'Bird Products', 'Other Pets'],
                    datasets: [{
                        data: [45, 30, 15, 10],
                        backgroundColor: ['#0099ff', '#36b9cc', '#ffcc00', '#adb5bd'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '70%'
                }
            });

            // Toggle sidebar functionality
            const toggleBtn = document.querySelector('.navbar-toggler');
            const sidebar = document.querySelector('.sidebar');
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar && sidebar.contains(event.target);
                const isClickInsideToggle = toggleBtn && toggleBtn.contains(event.target);
                
                if (sidebar && !isClickInsideSidebar && !isClickInsideToggle && window.innerWidth < 576) {
                    sidebar.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>