<?php
// Start session to manage cart data
session_start();

// Initialize cart in session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Simple product database (in real application this would come from a database)
$products = [
    1 => [
        'id' => 1,
        'name' => 'Pet Carrier',
        'price' => 1230.00,
        'image' => 'images/products/pet-carrier.png',
        'category' => 'accessories'
    ],
    2 => [
        'id' => 2,
        'name' => 'Cat Bowl Blue',
        'price' => 280.00,
        'image' => 'images/products/cat-bowl-blue.png',
        'category' => 'bowls'
    ],
    3 => [
        'id' => 3,
        'name' => 'Cat Bowl Yellow',
        'price' => 180.00,
        'image' => 'images/products/cat-bowl-yellow.png',
        'category' => 'bowls'
    ],
    4 => [
        'id' => 4,
        'name' => 'Premium Dog Food',
        'price' => 510.00,
        'image' => 'images/products/premium-dog-food.png',
        'category' => 'food'
    ],
    5 => [
        'id' => 5,
        'name' => 'Dog Bowl',
        'price' => 180.00,
        'image' => 'images/products/dog-bowl.png',
        'category' => 'bowls'
    ],
    6 => [
        'id' => 6,
        'name' => 'Dog Leash',
        'price' => 115.00,
        'image' => 'images/products/dog-leash.png',
        'category' => 'accessories'
    ],
    7 => [
        'id' => 7,
        'name' => 'Cat Barrier Bag',
        'price' => 1810.00,
        'image' => 'images/products/cat-carrier.png',
        'category' => 'accessories'
    ],
    8 => [
        'id' => 8,
        'name' => 'Salmon Cat Food',
        'price' => 540.00,
        'image' => 'images/products/premium-cat-food.png',
        'category' => 'food'
    ],
    9 => [
        'id' => 9,
        'name' => 'Dog Bed',
        'price' => 980.00,
        'image' => 'images/products/Dog-bed.png',
        'category' => 'beds'
    ]
];

// Popular products for sidebar
$popular_products = [
    ['name' => 'Premium Dog Food', 'price' => 395, 'image' => 'images/products/premium-dog-food.png'],
    ['name' => 'Premium Cat Food', 'price' => 329, 'image' => 'images/products/premium-cat-food.png'],
    ['name' => 'Cat Bed', 'price' => 1300, 'image' => 'images/products/cat-bed.png'],
    ['name' => 'Dog Leash', 'price' => 280, 'image' => 'images/products/dog-leash.png']
];

// Filter products based on request parameters
$filtered_products = $products;

// Category filter
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $categories = explode(',', $_GET['category']);
    $filtered_products = array_filter($filtered_products, function($product) use ($categories) {
        return in_array($product['category'], $categories);
    });
}

// Price filter
if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
    $max_price = (float)$_GET['max_price'];
    $filtered_products = array_filter($filtered_products, function($product) use ($max_price) {
        return $product['price'] <= $max_price;
    });
}

// Sort products
if (isset($_GET['sort'])) {
    switch ($_GET['sort']) {
        case 'price-low-high':
            usort($filtered_products, function($a, $b) {
                return $a['price'] <=> $b['price'];
            });
            break;
        case 'price-high-low':
            usort($filtered_products, function($a, $b) {
                return $b['price'] <=> $a['price'];
            });
            break;
        case 'latest':
        default:
            // In a real application, you'd sort by date added
            // Here we'll just keep the default order
            break;
    }
}

// Handle AJAX requests for cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'add_to_cart' && isset($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];
        
        // Check if product exists
        if (array_key_exists($product_id, $products)) {
            // Add to cart or increment quantity
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']++;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'product' => $products[$product_id],
                    'quantity' => 1
                ];
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Product added to cart',
                'cartCount' => count($_SESSION['cart'])
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }
        exit;
    }
}

// Function to check if a category is selected
function isCategoryChecked($category) {
    if (isset($_GET['category'])) {
        $categories = explode(',', $_GET['category']);
        return in_array($category, $categories);
    }
    return false;
}

// Function to check if a sort option is selected
function isSortSelected($sort) {
    return isset($_GET['sort']) && $_GET['sort'] === $sort;
}

// Function to get the current page URL with modified query parameters
function getUrlWithParams($params = []) {
    $current_params = $_GET;
    $new_params = array_merge($current_params, $params);
    return 'shop.php?' . http_build_query($new_params);
}

// Calculate cart count
$cart_count = count($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop in PawShop</title>
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

        .btn-orange {
            background-color: orange;
            border-color: orange;
            color: white;
            transition: var(--transition);
        }

        .btn-orange:hover {
            background-color: darkorange;
            border-color: darkorange;
            color: white;
        }

        /* Change heart icon color to red on hover */
        .add-to-wishlist:hover i {
            color: red;
        }

        /* Header & Navigation */
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

        /* Hero Section */
        .hero-section {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
            border-radius: 0 0 30px 30px;
            box-shadow: var(--box-shadow);
        }

        .hero-section h1 {
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-section .lead {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        .hero-section .btn-primary {
            background-color: var(--white);
            color: var(--primary-color);
            border-color: var(--white);
            padding: 12px 25px;
        }

        .hero-section .btn-outline-primary {
            color: var(--white);
            border-color: var(--white);
            padding: 12px 25px;
        }

        .hero-section .btn-outline-primary:hover {
            background-color: var(--white);
            color: var(--primary-color);
        }

        .hero-image {
            transform: scale(1.1);
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.2));
            transition: transform 0.5s ease;
        }

        .hero-image:hover {
            transform: scale(1.15) translateY(-5px);
        }

        /* Category Section */
        .category-section {
            padding: 4rem 0;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 10px;
        }

        .section-subtitle {
            color: var(--light-text);
            margin-bottom: 3rem;
        }

        .category-item {
            transition: var(--transition);
            cursor: pointer;
            padding: 20px 15px;
            border-radius: var(--border-radius);
        }

        .category-item:hover {
            transform: translateY(-8px);
            background-color: var(--gray-100);
            box-shadow: var(--box-shadow);
        }

        .icon-bg {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background-color: var(--primary-color);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .category-item:hover .icon-bg {
            border-radius: 30px;
            transform: rotate(5deg);
        }

        .category-icon {
            width: 40px;
            height: 40px;
            filter: brightness(0) invert(1);
        }

        .category-name {
            font-weight: 600;
            margin-top: 1rem;
            color: var(--text-color);
        }

        /* Product Cards */
        .product-section {
            background-color: var(--gray-100);
            padding: 4rem 0;
            border-radius: 30px;
        }

        .product-card {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            border: none;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
        }

        .product-card .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .product-card .card-body {
            padding: 1.5rem;
        }

        .product-card .card-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--accent-color);
            color: var(--text-color);
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .price {
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--primary-color);
        }

        .old-price {
            text-decoration: line-through;
            color: var(--light-text);
            font-size: 0.9rem;
            margin-right: 8px;
        }

        .product-card .btn-primary {
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        .product-card .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--gray-100);
            color: var(--primary-color);
            border: none;
            margin-left: 10px;
            transition: var(--transition);
        }

        .product-card .btn-icon:hover {
            background-color: var(--primary-color);
            color: var(--white);
        }

        /* Partners Section */
        .partners-section {
            padding: 4rem 0;
        }

        .partner-logo {
            max-height: 60px;
            filter: opacity(0.7);
            transition: var(--transition);
        }

        .partner-logo:hover {
            filter: opacity(1);
            transform: scale(1.05);
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

        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--white);
            color: var(--text-color);
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            border-left: 4px solid var(--primary-color);
        }
        
        .toast-notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .toast-content {
            display: flex;
            align-items: center;
        }
        
        .toast-content i {
            margin-right: 15px;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .toast-notification .btn-close {
            margin-left: 15px;
            opacity: 0.5;
            transition: var(--transition);
        }

        .toast-notification .btn-close:hover {
            opacity: 1;
        }

        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 999;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: var(--primary-dark);
            color: var(--white);
            transform: translateY(-5px);
        }

        /* Animation Classes */
        .fade-in-up {
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .hero-section {
                padding: 4rem 0;
                text-align: center;
            }
            
            .hero-image {
                margin-top: 2rem;
            }
            
            .category-item {
                margin-bottom: 1.5rem;
            }
            
            .product-card {
                margin-bottom: 30px;
            }
        }

        @media (max-width: 767.98px) {
            .navbar .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .category-row {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 1rem;
                margin-bottom: 1rem;
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            
            .category-row::-webkit-scrollbar {
                display: none;
            }
            
            .category-col {
                min-width: 140px;
            }
            
            .section-title:after {
                width: 40px;
            }
            
            .footer {
                padding-top: 3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header/Navigation -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="images/pawshop-logo.png" alt="PawShop Logo" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="shop.php">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <form action="search.php" method="GET" class="d-flex me-3">
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search...">
                        <button type="submit" class="btn btn-sm btn-primary ms-2"><i class="fas fa-search"></i></button>
                    </form>
                    <a href="favorites.php" class="nav-icon text-dark ms-3"><i class="fas fa-heart"></i></a>
                    <a href="account.php" class="nav-icon text-dark ms-3"><i class="fas fa-user"></i></a>
                    <a href="cart.php" class="nav-icon text-dark position-relative ms-3">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                <?php echo $cart_count; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <div class="container py-4">
        <h1 class="mb-4">Shop</h1>
        
        <div class="row">
            <!-- Sidebar with filters -->
            <div class="col-lg-3">
                <!-- Categories filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Filter by categories</h5>
                        <form id="categoryFilterForm" action="shop.php" method="GET">
                            <?php if(isset($_GET['sort'])): ?>
                                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($_GET['sort']); ?>">
                            <?php endif; ?>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input category-checkbox" type="checkbox" name="category[]" value="bowls" id="category-bowls" <?php echo isCategoryChecked('bowls') ? 'checked' : ''; ?>>
                                <label class="form-check-label d-flex justify-content-between align-items-center" for="category-bowls">
                                    Bowls <span class="text-muted">39</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input category-checkbox" type="checkbox" name="category[]" value="clothing" id="category-clothing" <?php echo isCategoryChecked('clothing') ? 'checked' : ''; ?>>
                                <label class="form-check-label d-flex justify-content-between align-items-center" for="category-clothing">
                                    Clothing <span class="text-muted">12</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input category-checkbox" type="checkbox" name="category[]" value="food" id="category-food" <?php echo isCategoryChecked('food') ? 'checked' : ''; ?>>
                                <label class="form-check-label d-flex justify-content-between align-items-center" for="category-food">
                                    Food <span class="text-muted">81</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input category-checkbox" type="checkbox" name="category[]" value="toys" id="category-toys" <?php echo isCategoryChecked('toys') ? 'checked' : ''; ?>>
                                <label class="form-check-label d-flex justify-content-between align-items-center" for="category-toys">
                                    Toys <span class="text-muted">50</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input category-checkbox" type="checkbox" name="category[]" value="beds" id="category-beds" <?php echo isCategoryChecked('beds') ? 'checked' : ''; ?>>
                                <label class="form-check-label d-flex justify-content-between align-items-center" for="category-beds">
                                    Beds <span class="text-muted">24</span>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary mt-2">Apply Filters</button>
                        </form>
                    </div>
                </div>

                <!-- Price filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Filter by Price</h5>
                        <form id="priceFilterForm" action="shop.php" method="GET">
                            <?php if(isset($_GET['category'])): ?>
                                <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                            <?php endif; ?>
                            
                            <?php if(isset($_GET['sort'])): ?>
                                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($_GET['sort']); ?>">
                            <?php endif; ?>
                            
                            <div class="range">
                                <input type="range" class="form-range" min="0" max="5000" step="50" id="priceRange" name="max_price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '5000'; ?>">
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>₱250</span>
                                <span id="priceRangeValue">₱<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '5000'; ?></span>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary mt-3 float-end">Apply</button>
                            <div class="clearfix"></div>
                        </form>
                        </form>
                    </div>
                </div>

                <!-- Popular products sidebar -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Popular Products</h5>
                        <?php foreach($popular_products as $product): ?>
                        <div class="d-flex mb-3">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-fluid rounded" style="width: 70px; height: 70px; object-fit: cover;">
                            <div class="ms-3">
                                <div class="small fw-bold"><?php echo $product['name']; ?></div>
                                <div class="text-primary">₱<?php echo number_format($product['price'], 2); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Main product list -->
            <div class="col-lg-9">
                <!-- Sort options -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span>Showing <?php echo count($filtered_products); ?> products</span>
                    </div>
                    <div>
                        <select id="sortSelect" class="form-select form-select-sm">
                            <option value="latest" <?php echo isSortSelected('latest') ? 'selected' : ''; ?>>Latest</option>
                            <option value="price-low-high" <?php echo isSortSelected('price-low-high') ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price-high-low" <?php echo isSortSelected('price-high-low') ? 'selected' : ''; ?>>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Products grid -->
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach($filtered_products as $product): ?>
                    <div class="col mb-4">
                        <div class="card product-card h-100">
                            <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="price">₱<?php echo number_format($product['price'], 2); ?></span>
                                    <div>
                                        <button class="btn btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                            <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-muted small">Category: <?php echo ucfirst($product['category']); ?></span>
                                    <button class="btn btn-outline-secondary btn-sm add-to-wishlist" data-product-id="<?php echo $product['id']; ?>">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
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

    <!-- Toast notification -->
    <div class="toast-notification" id="toastNotification">
        <div class="toast-content">
            <i class="fas fa-check-circle"></i>
            <div>
                <span id="toastMessage">Item added to cart!</span>
            </div>
        </div>
        <button type="button" class="btn-close" id="closeToast"></button>
    </div>

    <!-- Back to top button -->
    <a href="#" class="back-to-top" id="backToTop"><i class="fas fa-arrow-up"></i></a>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Price range slider
            const priceRange = document.getElementById('priceRange');
            const priceRangeValue = document.getElementById('priceRangeValue');
            
            if (priceRange) {
                priceRange.addEventListener('input', function() {
                    priceRangeValue.textContent = '₱' + this.value;
                });
            }
            
            // Sort select
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('sort', this.value);
                    window.location.href = currentUrl.toString();
                });
            }
            
            // Add to cart functionality
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    
                    // Send AJAX request
                    fetch('shop.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=add_to_cart&product_id=' + productId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show notification
                            showToast(data.message);
                            
                            // Update cart count in navigation
                            const cartBadge = document.querySelector('.fa-shopping-cart').nextElementSibling;
                            if (cartBadge) {
                                cartBadge.textContent = data.cartCount;
                            } else {
                                const cartIcon = document.querySelector('.fa-shopping-cart');
                                const badge = document.createElement('span');
                                badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary';
                                badge.textContent = data.cartCount;
                                cartIcon.parentNode.appendChild(badge);
                            }
                        } else {
                            showToast('Failed to add product to cart');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
            
            // Toast notification
            const toast = document.getElementById('toastNotification');
            const closeToast = document.getElementById('closeToast');
            
            function showToast(message) {
                const toastMessage = document.getElementById('toastMessage');
                toastMessage.textContent = message;
                toast.classList.add('show');
                
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }
            
            if (closeToast) {
                closeToast.addEventListener('click', function() {
                    toast.classList.remove('show');
                });
            }
            
            // Back to top button
            const backToTopButton = document.getElementById('backToTop');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('show');
                } else {
                    backToTopButton.classList.remove('show');
                }
            });
            
            if (backToTopButton) {
                backToTopButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
            
            // Add to wishlist
            const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const heartIcon = this.querySelector('i');
                    if (heartIcon.classList.contains('far')) {
                        heartIcon.classList.remove('far');
                        heartIcon.classList.add('fas');
                        heartIcon.style.color = 'red';
                        showToast('Added to wishlist!');
                    } else {
                        heartIcon.classList.remove('fas');
                        heartIcon.classList.add('far');
                        heartIcon.style.color = '';
                        showToast('Removed from wishlist!');
                    }
                });
            });
        });
    </script>
</body>
</html>
                    