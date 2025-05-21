<?php
// Define the routes for navigation
$routes = [
    'home' => 'index.php',
    'shop' => 'shop.php',
    'about' => 'about.php',
    'contact' => 'contact.php',
    'favorites' => 'favorites.php',
    'account' => 'account.php',
    'cart' => 'cart.php'
];

// Helper function to check if current page matches route
function isCurrentRoute($route) {
    $current_page = basename($_SERVER['PHP_SELF']);
    if ($route === 'home' && $current_page === 'index.php') {
        return true;
    }
    return $current_page === $route . '.php';
}

// Mock cart data for demonstration
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Sample user data - would typically come from a database
$userData = [
    'name' => 'Username',
    'membership' => 'Pet Club',
    'coupons' => 3,
    'points' => 500,
    'wallet' => 250
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawShop - My Account</title>
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
            background-color: #f8f9fa;
        }

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

        .btn-orange {
            background-color: orange;
            border-color: orange;
            color: white;
        }

        .btn-orange:hover {
            background-color: darkorange;
            border-color: darkorange;
            color: white;
        }

        .navbar {
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-weight: 700;
        }

        .nav-link {
            font-weight: 500;
            padding: 8px 16px !important;
            margin-right: 5px;
            border-radius: 20px;
            transition: var(--transition);
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(0, 153, 255, 0.1);
            color: var(--primary-color) !important;
        }

        .nav-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: var(--gray-100);
            transition: var(--transition);
        }

        .nav-icon:hover {
            background-color: var(--primary-color);
            color: var(--white) !important;
        }

        .account-sidebar {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .account-main {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 5px;
            color: var(--text-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .sidebar-link:hover, .sidebar-link.active {
            background-color: rgba(0, 153, 255, 0.1);
            color: var(--primary-color);
        }

        .sidebar-link i {
            margin-right: 10px;
            min-width: 20px;
            text-align: center;
        }

        .section-header {
            font-weight: 700;
            padding: 15px 20px;
            margin-bottom: 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .badge-club {
            background-color: orange;
            color: white;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: 8px;
        }

        .badge-points {
            background-color: #28a745;
            color: white;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: 8px;
        }

        .account-stats {
            display: flex;
            justify-content: space-between;
        }

        .stat-item {
            flex: 1;
            text-align: center;
            padding: 20px 10px;
            border-right: 1px solid var(--gray-200);
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--light-text);
        }

        .order-item {
            padding: 15px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-date {
            color: var(--light-text);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .order-image {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            object-fit: cover;
            margin-right: 15px;
        }

        .order-price {
            font-weight: 600;
            color: var(--primary-color);
        }

        .order-details {
            flex: 1;
        }

        .order-status {
            background-color: #e6f7ff;
            color: #0099ff;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .notification {
            background-color: #fff8e1;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 3px solid orange;
        }

        .notification .close {
            cursor: pointer;
        }

        .promo-card {
            background-color: #fff0e8;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .service-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 15px;
        }

        .service-icon {
            width: 50px;
            height: 50px;
            background-color: #e6f7ff;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .service-counter {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ff4d4f;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0.75rem 0;
        }

        .breadcrumb-item a {
            color: var(--light-text);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--text-color);
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        /* Orders tab styles */
        .order-tab {
            display: flex;
            overflow-x: auto;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .order-tab-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px 20px;
            min-width: 100px;
            cursor: pointer;
            transition: var(--transition);
            border-bottom: 2px solid transparent;
        }

        .order-tab-item.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
        }

        .order-tab-icon {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .order-notification {
            position: absolute;
            top: 0;
            right: 5px;
            background-color: #ff4d4f;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 5rem 0 2rem;
            border-radius: 30px 30px 0 0;
        }

        .footer-widget h4 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }

        .footer-widget h4:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: var(--white);
        }

        .footer-links {
            margin-top: 1.5rem;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: inline-block;
        }

        .footer-links a:hover {
            color: var(--white);
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            margin-top: 1.5rem;
        }

        .social-links a {
            display: flex;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--white);
            margin-right: 10px;
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--white);
            color: var(--primary-color);
            transform: translateY(-5px);
        }

        .payment-icon {
            height: 30px;
            margin-left: 15px;
            filter: brightness(0) invert(1);
            transition: var(--transition);
        }

        .payment-icon:hover {
            transform: scale(1.1);
        }

        hr.footer-divider {
            border-color: rgba(255, 255, 255, 0.2);
            margin: 2.5rem 0;
        }

        .copyright {
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <!-- Header/Navigation -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
            <div class="container">
                <a class="navbar-brand" href="<?php echo $routes['home']; ?>">
                    <img src="images/pawshop-logo.png" alt="PawShop Logo" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo isCurrentRoute('home') ? 'active' : ''; ?>" href="<?php echo $routes['home']; ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isCurrentRoute('shop') ? 'active' : ''; ?>" href="<?php echo $routes['shop']; ?>">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isCurrentRoute('about') ? 'active' : ''; ?>" href="<?php echo $routes['about']; ?>">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isCurrentRoute('contact') ? 'active' : ''; ?>" href="<?php echo $routes['contact']; ?>">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Search component -->
                    <div class="position-relative">
                        <form action="search.php" method="GET" class="d-flex">
                            <input type="text" name="q" class="form-control form-control-sm rounded-pill" placeholder="Search...">
                            <button type="submit" class="btn btn-sm btn-link position-absolute end-0">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <a href="<?php echo $routes['favorites']; ?>" class="nav-icon text-dark ms-3"><i class="fas fa-heart"></i></a>
                    <a href="<?php echo $routes['account']; ?>" class="nav-icon text-dark ms-3"><i class="fas fa-user"></i></a>
                    <a href="<?php echo $routes['cart']; ?>" class="nav-icon text-dark position-relative ms-3">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                <?php echo count($_SESSION['cart']); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Breadcrumb -->
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $routes['home']; ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Personal Center</li>
            </ol>
        </nav>
    </div>

    <!-- Account Dashboard -->
    <div class="container py-4">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="account-sidebar p-4 mb-4">
                    <h3 class="section-header border-bottom pb-3 mb-3">Personal Center</h3>
                    
                    <!-- My Account Section -->
                    <div class="mb-4">
                        <h6 class="sidebar-section-title fw-bold mb-3">My Account</h6>
                        <a href="#" class="sidebar-link mb-2 active">
                            <i class="fas fa-user-circle"></i> Paw Club <span class="badge-club">CLUB</span>
                        </a>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-crown"></i> Paw VIP
                        </a>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-user"></i> My Profile <span class="badge-points">POINTS</span>
                        </a>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-address-book"></i> Address
                        </a>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-credit-card"></i> My Payment Options
                        </a>
                    </div>
                    
                    <!-- My Orders Section -->
                    <div class="mb-4">
                        <h6 class="sidebar-section-title fw-bold mb-3 d-flex justify-content-between align-items-center">
                            My Orders
                            <i class="fas fa-plus"></i>
                        </h6>
                    </div>
                    
                    <!-- My Favorites Section -->
                    <div class="mb-4">
                        <h6 class="sidebar-section-title fw-bold mb-3 d-flex justify-content-between align-items-center">
                            My Favorites
                            <i class="fas fa-plus"></i>
                        </h6>
                    </div>
                    
                    <!-- Customer Service Section -->
                    <div class="mb-4">
                        <h6 class="sidebar-section-title fw-bold mb-3 d-flex justify-content-between align-items-center">
                            Customer Service
                            <i class="fas fa-plus"></i>
                        </h6>
                    </div>
                    
                    <!-- Other Services Section -->
                    <div class="mb-4">
                        <h6 class="sidebar-section-title fw-bold mb-3 d-flex justify-content-between align-items-center">
                            Other Services
                            <i class="fas fa-minus"></i>
                        </h6>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-tag"></i> My Free Trial
                        </a>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-poll"></i> Survey Center
                        </a>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-share-alt"></i> Share&Earn
                        </a>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-envelope"></i> Contact Preferences
                        </a>
                    </div>
                    
                    <!-- Policy Section -->
                    <div class="mb-4">
                        <h6 class="sidebar-section-title fw-bold mb-3 d-flex justify-content-between align-items-center">
                            Policy
                            <i class="fas fa-minus"></i>
                        </h6>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-shipping-fast"></i> Shipping Info
                        </a>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-undo"></i> Return Policy
                        </a>
                        <a href="#" class="sidebar-link mb-2">
                            <i class="fas fa-shield-alt"></i> Privacy Policy & Cookies
                        </a>
                    </div>
                    
                    <!-- Sign Out -->
                    <a href="logout.php" class="sidebar-link">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </a>
                </div>
            </div>
            
            <!-- Right Content Area -->
            <div class="col-lg-9 col-md-8">
                <!-- Welcome Section -->
                <div class="account-main p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-1">Hi, <?php echo htmlspecialchars($userData['name']); ?> <span class="badge bg-dark"><?php echo htmlspecialchars($userData['membership']); ?></span></h4>
                            <p class="text-muted m-0">Welcome back to your account</p>
                        </div>
                        <a href="profile.php" class="btn btn-sm btn-light">My Profile <i class="fas fa-chevron-right ms-1"></i></a>
                    </div>
                    
                    <!-- Account Stats -->
                    <div class="account-stats border rounded">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo $userData['coupons']; ?></div>
                            <div class="stat-label">Coupons</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo $userData['points']; ?></div>
                            <div class="stat-label">Points</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">₱<?php echo $userData['wallet']; ?></div>
                            <div class="stat-label">Wallet</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><i class="fas fa-gift"></i></div>
                            <div class="stat-label">Gift Card</div>
                        </div>
                    </div>
                    
                    <!-- Notification -->
                    <div class="notification mt-4 d-flex justify-content-between align-items-center">
                        <div>You have <?php echo $userData['coupons']; ?> coupon(s) that will expire!</div>
                        <div class="close">&times;</div>
                    </div>
                    
                    <!-- PawClub Promo -->
                    <div class="promo-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-paw me-2 text-warning"></i>
                                <span class="fw-bold">PAW CLUB</span>
                            </div>
                            <a href="club-signup.php" class="btn btn-sm btn-orange">Join Now <i class="fas fa-chevron-right ms-1"></i></a>
                        </div>
                        <p class="my-3">Extra 5% OFF 100k+ items.</p>
                        <div class="d-flex justify-content-around mt-3">
                            <div class="text-center">
                                <i class="fas fa-percentage text-warning"></i>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-gift text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- My Orders Section -->
                <div class="account-main p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="m-0">My Orders</h5>
                        <a href="orders.php" class="btn btn-sm btn-light">View All <i class="fas fa-chevron-right ms-1"></i></a>
                    </div>
                    
                    <!-- Order Tabs -->
                    <div class="order-tab">
                        <div class="order-tab-item text-center position-relative active">
                            <i class="fas fa-file-invoice-dollar order-tab-icon"></i>
                            <span>Unpaid</span>
                        </div>
                        <div class="order-tab-item text-center position-relative">
                            <i class="fas fa-box-open order-tab-icon"></i>
                            <span>Processing</span>
                        </div>
                        <div class="order-tab-item text-center position-relative">
                            <i class="fas fa-shipping-fast order-tab-icon"></i>
                            <span>Shipped</span>
                        </div>
                        <div class="order-tab-item text-center position-relative">
                            <i class="fas fa-comment-dots order-tab-icon"></i>
                            <span>Review</span>
                            <span class="order-notification">2</span>
                        </div>
                        <div class="order-tab-item text-center position-relative">
                            <i class="fas fa-undo order-tab-icon"></i>
                            <span>Returns</span>
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <div class="order-list mt-3">
                        <?php
                        // Sample order data - would typically come from database
                        $orders = [
                            [
                                'date' => '12/28/2024 21:03:35',
                                'image' => 'images/product1.png',
                                'items' => 1,
                                'price' => 187,
                                'status' => 'Delivered',
                                'id' => 'ORD123456'
                            ],
                            [
                                'date' => '11/27/2024 16:18:45',
                                'image' => 'images/product2.png',
                                'items' => 3,
                                'price' => 484,
                                'status' => 'Delivered',
                                'id' => 'ORD123457'
                            ]
                        ];
                        
                        foreach ($orders as $order):
                        ?>
                        <div class="order-item">
                            <div class="order-date"><?php echo htmlspecialchars($order['date']); ?></div>
                            <img src="<?php echo htmlspecialchars($order['image']); ?>" alt="Order image" class="order-image">
                            <div class="order-details">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div><?php echo $order['items']; ?> item<?php echo $order['items'] > 1 ? 's' : ''; ?></div>
                                    <div class="order-price">₱<?php echo $order['price']; ?></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="order-status"><?php echo htmlspecialchars($order['status']); ?></div>
                                    <a href="order-details.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="btn btn-sm btn-primary">Order Details</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Customer Service -->
                <div class="account-main p-4 mb-4">
                    <h5 class="mb-4">Customer Service</h5>
                    <div class="row">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="service-item position-relative">
                                <div class="service-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <span class="service-counter">1</span>
                                <div class="service-title">My Messages</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="service-item">
                                <div class="service-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="service-title">Service Records</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Other Sections -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="account-main p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="m-0">Wishlist</h6>
                                <span class="text-muted">0 item <i class="fas fa-chevron-right ms-1"></i></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="account-main p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="m-0">Following</h6>
                                <span class="text-muted">0 Following <i class="fas fa-chevron-right ms-1"></i></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="account-main p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="m-0">Recently Viewed</h6>
                                <span class="text-muted">More <i class="fas fa-chevron-right ms-1"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4>PawShop</h4>
                        <p>Your one-stop shop for all pet needs. We provide quality products for your furry, feathery, or scaly friends.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4>Shop</h4>
                        <ul class="list-unstyled footer-links">
                            <li><a href="shop.php?category=food">Pet Food</a></li>
                            <li><a href="shop.php?category=toys">Pet Toys</a></li>
                            <li><a href="shop.php?category=accessories">Accessories</a></li>
                            <li><a href="shop.php?category=health">Health Care</a></li>
                            <li><a href="shop.php?category=grooming">Grooming</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4>Information</h4>
                        <ul class="list-unstyled footer-links">
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="contact.php">Contact Us</a></li>
                            <li><a href="privacy-policy.php">Privacy Policy</a></li>
                            <li><a href="terms.php">Terms & Conditions</a></li>
                            <li><a href="faq.php">FAQs</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4>Contact</h4>
                        <p><i class="fas fa-map-marker-alt me-2"></i> 123 Main Street, Anytown, PH</p>
                        <p><i class="fas fa-phone-alt me-2"></i> +63 123 456 7890</p>
                        <p><i class="fas fa-envelope me-2"></i> support@pawshop.com</p>
                        <div class="d-flex align-items-center mt-4">
                            <span class="me-2">We Accept:</span>
                            <img src="images/payment/visa.png" alt="Visa" class="payment-icon">
                            <img src="images/payment/mastercard.png" alt="Mastercard" class="payment-icon">
                            <img src="images/payment/paypal.png" alt="PayPal" class="payment-icon">
                        </div>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="copyright">© <?php echo date('Y'); ?> PawShop. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script for closing notifications
        document.querySelectorAll('.notification .close').forEach(close => {
            close.addEventListener('click', function() {
                this.closest('.notification').style.display = 'none';
            });
        });
        
        // Order tab interactions
        document.querySelectorAll('.order-tab-item').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.order-tab-item').forEach(item => {
                    item.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
        
        // Mobile sidebar toggle
        const toggleSidebar = (sectionTitle) => {
            const icon = sectionTitle.querySelector('i');
            if (icon.classList.contains('fa-plus')) {
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            } else {
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
            
            const links = sectionTitle.nextElementSibling;
            if (links && links.classList.contains('sidebar-link')) {
                let currentLink = links;
                while (currentLink && currentLink.classList.contains('sidebar-link')) {
                    currentLink.style.display = currentLink.style.display === 'none' ? 'flex' : 'none';
                    currentLink = currentLink.nextElementSibling;
                }
            }
        };
        
        document.querySelectorAll('.sidebar-section-title').forEach(title => {
            title.addEventListener('click', () => toggleSidebar(title));
        });
        
        // Responsive adjustments
        const adjustLayout = () => {
            if (window.innerWidth < 768) {
                document.querySelectorAll('.sidebar-section-title:not(:first-of-type)').forEach(title => {
                    const icon = title.querySelector('i');
                    if (icon.classList.contains('fa-minus')) {
                        toggleSidebar(title);
                    }
                });
            }
        };
        
        window.addEventListener('load', adjustLayout);
        window.addEventListener('resize', adjustLayout);
    </script>
</body>
</html>