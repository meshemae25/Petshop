<?php
// Mock data that would typically come from a database
$categories = [
    ['name' => 'Toys & Treats', 'icon' => 'toy.png'],
    ['name' => 'Time & Training', 'icon' => 'hk.png'],
    ['name' => 'Travel & Transit', 'icon' => 'ls.png'],
    ['name' => 'Tags & Tracking', 'icon' => 'tags.png'],
    ['name' => 'Health & Hygiene', 'icon' => 'hygiene.png']
];

$products = [
    [
        'id' => 1,
        'name' => 'Premium Dog Food',
        'description' => 'High-quality nutrition for your furry friend with all natural ingredients',
        'price' => 134.99,
        'old_price' => 242.99,
        'image' => 'product2.png',
        'rating' => 4.5,
        'reviews' => 42,
        'badge' => 'Best Seller'
    ],
    [
        'id' => 2,
        'name' => 'Deluxe Cat Tree',
        'description' => 'Multi-level cat tree with scratching posts, toys, and cozy hideaways',
        'price' => 269.99,
        'old_price' => 389.99,
        'image' => 'product3.png',
        'rating' => 4.0,
        'reviews' => 36,
        'badge' => 'Sale'
    ],
    [
        'id' => 3,
        'name' => 'Smart Pet Collar',
        'description' => 'GPS tracking and health monitoring for your pet with smartphone app',
        'price' => 149.99,
        'image' => 'product1.png',
        'rating' => 5.0,
        'reviews' => 28,
        'badge' => 'New'
    ]
];

// Helper functions to replace Laravel's route() helper
function route($name, $params = null) {
    $routes = [
        'home' => 'index.php',
        'shop' => 'shop.php',
        'about' => 'about.php',
        'contact' => 'contact.php',
        'favorites' => 'favorites.php',
        'account' => 'account.php',
        'cart' => 'cart.php',
        'loyalty' => 'loyalty.php',
        'faq' => 'faq.php',
        'terms' => 'terms.php',
        'privacy' => 'privacy.php',
        'category' => 'category.php'
    ];
    
    if (isset($routes[$name])) {
        if ($name === 'category' && $params) {
            return $routes[$name] . '?category=' . $params;
        } elseif ($name === 'cart.add' && $params) {
            return 'cart.php?action=add&id=' . $params;
        } elseif ($name === 'wishlist.toggle' && $params) {
            return 'wishlist.php?action=toggle&id=' . $params;
        }
        return $routes[$name];
    }
    
    return '#';
}

// Helper function to replace Laravel's asset() helper
function asset($path) {
    return $path;
}

// Check if current route matches to highlight active navigation
function request_is($route) {
    $current_page = basename($_SERVER['PHP_SELF']);
    $routes = [
        'home' => 'index.php',
        'shop' => 'shop.php',
        'about' => 'about.php',
        'contact' => 'contact.php'
    ];
    
    if (isset($routes[$route]) && $routes[$route] === $current_page) {
        return true;
    }
    
    return false;
}

// Mock cart count function
function get_cart_count() {
    // In a real app this would come from the session or database
    return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - PawShop</title>
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

        /* Hero Section - Updated with blue gradient background */
        .hero-section {
        background: linear-gradient(135deg, #00a1ff 0%, #0077ff 100%);
        color: var(--white);
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
        border-radius: 0 0 30px 30px;
        box-shadow: var(--box-shadow);
        }

        .hero-section:before {
        content: '';
        position: absolute;
        top: -100px;
        left: -100px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0) 70%);
        z-index: 0;
        }

        /* Make sure content is above the gradient */
        .hero-section .container {
        position: relative;
        z-index: 2;
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

/* Updated button styles to match Image 1 */
.hero-section .btn-primary {
    background-color: var(--white);
    color: var(--primary-color);
    border-color: var(--white);
    padding: 12px 25px;
}

.hero-section .btn-orange {
    background-color: #ffcc00;
    color: var(--text-color);
    border-color: #ffcc00;
    padding: 12px 25px;
    font-weight: 600;
}

.hero-section .btn-orange:hover {
    background-color: #ffc000;
    border-color: #ffc000;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Add a yellow circular background behind the puppy */
.hero-image-container {
    position: relative;
    padding: 20px;
}

.hero-image-container:before {
    content: '';
    position: absolute;
    top: 0;
    left: 30px;
    width: 80%;
    height: 80%;
    border-radius: 50%;
    z-index: 1;
}

.hero-image {
    position: relative;
    z-index: 2;
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

        .carousel-control-next {
            width: 50px;
            height: 50px;
            top: 50%;
            transform: translateY(-50%);
            background-color: var(--white);
            border-radius: 50%;
            box-shadow: var(--box-shadow);
            opacity: 1;
            margin-right: -25px;
        }

        .carousel-control-next-icon {
            width: 20px;
            height: 20px;
            background-color: var(--primary-color);
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

        /* Testimonials Section */
        .testimonials-section {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 5rem 0;
            position: relative;
            border-radius: 30px;
            margin: 4rem 0;
        }

        .testimonial-img-container {
            width: 180px;
            height: 180px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            border: 5px solid var(--white);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

        .testimonial-img-container:hover {
            transform: scale(1.05) rotate(2deg);
            border-radius: 30px;
        }

        .testimonial-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .testimonial-card {
            border-radius: 15px;
            padding: 1.5rem;
            background-color: var(--white);
            height: 100%;
            transition: var(--transition);
            box-shadow: var(--box-shadow);
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
        }

        .testimonial-text {
            font-style: italic;
            font-size: 0.95rem;
            color: var(--text-color);
            margin-bottom: 1rem;
            position: relative;
        }

        .testimonial-text:before {
            content: '"';
            font-size: 3rem;
            position: absolute;
            left: -10px;
            top: -20px;
            color: rgba(0, 153, 255, 0.1);
            font-family: serif;
        }

        .testimonial-card .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }

        .testimonial-card .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .testimonial-card .customer-info {
            flex: 1;
        }

        .testimonial-card .customer-name {
            font-weight: 700;
            margin-bottom: 0;
        }

        .testimonial-card .customer-type {
            font-size: 0.8rem;
            color: var(--light-text);
        }

        .rating {
            font-weight: 600;
        }

        .rating i {
            color: var(--accent-color);
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
            
            .testimonial-img-container {
                width: 150px;
                height: 150px;
                margin-bottom: 2rem;
            }
            
            .testimonial-card {
                margin-bottom: 30px;
            }
        }

        @media (max-width: 767.98px) {
            .navbar .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .hero-section h1 {
                font-size: 2.5rem;
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
            
            .testimonial-section {
                padding: 3rem 0;
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
                <a class="navbar-brand" href="<?php echo route('home'); ?>">
                    <img src="<?php echo asset('images/pawshop-logo.png'); ?>" alt="PawShop Logo" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo request_is('home') ? 'active' : ''; ?>" href="<?php echo route('home'); ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo request_is('shop') ? 'active' : ''; ?>" href="<?php echo route('shop'); ?>">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo request_is('about') ? 'active' : ''; ?>" href="<?php echo route('about'); ?>">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo request_is('contact') ? 'active' : ''; ?>" href="<?php echo route('contact'); ?>">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Search component placeholder -->
                    <div class="position-relative me-3">
                        <input type="text" class="form-control rounded-pill" placeholder="Search...">
                    </div>
                    <a href="<?php echo route('favorites'); ?>" class="nav-icon text-dark ms-3"><i class="fas fa-heart"></i></a>
                    <a href="<?php echo route('account'); ?>" class="nav-icon text-dark ms-3"><i class="fas fa-user"></i></a>
                    <a href="<?php echo route('cart'); ?>" class="nav-icon text-dark position-relative ms-3">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if(get_cart_count() > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                <?php echo get_cart_count(); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 fade-in-up">
                    <h1 class="display-4">Everything Your Pet Needs, Just a Click Away!</h1>
                    <p class="lead">Shop premium pet food, accessories, grooming, and healthcare products online with ease!</p>
                    <div class="d-grid gap-3 d-md-flex">
                        <a href="<?php echo route('shop'); ?>" class="btn btn-primary px-4 py-2 me-md-3">Shop Now</a>
                        <a href="<?php echo route('loyalty'); ?>" class="btn btn-orange px-4 py-2">Create account</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image-container">
                        <img src="<?php echo asset('images/pug-puppy.png'); ?>" alt="Cute Puppy" class="img-fluid hero-image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop by Category Section -->
    <section class="category-section py-5 bg-white">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">Find everything your pet needs, organized by category</p>
            </div>
            <div class="row category-row g-4 justify-content-center">
                <?php if(!empty($categories)): ?>
                    <?php foreach($categories as $category): ?>
                        <div class="col category-col">
                            <div class="category-item text-center">
                                <div class="icon-bg">
                                    <img src="<?php echo asset('images/' . $category['icon']); ?>" alt="<?php echo $category['name']; ?>" class="category-icon">
                                </div>
                                <p class="category-name"><?php echo $category['name']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <h2 class="section-title">Popular Items</h2>
                <p class="section-subtitle">Check out our most loved pet products</p>
            </div>
            <div class="row">
                <?php if(!empty($products)): ?>
                    <?php foreach($products as $product): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="product-card card h-100">
                                <?php if(!empty($product['badge'])): ?>
                                    <div class="product-badge"><?php echo $product['badge']; ?></div>
                                <?php endif; ?>
                                <img src="<?php echo asset('images/' . $product['image']); ?>" alt="<?php echo $product['name']; ?>" class="card-img-top">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                    <div class="mb-2">
                                        <span class="rating">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= floor($product['rating'])): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php elseif($i - 0.5 <= $product['rating']): ?>
                                                    <i class="fas fa-star-half-alt"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </span>
                                        <span class="text-muted ms-2">(<?php echo $product['reviews']; ?> reviews)</span>
                                    </div>
                                    <p class="card-text"><?php echo $product['description']; ?></p>
                                    <div class="d-flex align-items-center mt-auto">
                                        <div>
                                            <?php if(!empty($product['old_price'])): ?>
                                                <span class="old-price">$<?php echo number_format($product['old_price'], 2); ?></span>
                                            <?php endif; ?>
                                            <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <a href="<?php echo route('cart.add', $product['id']); ?>" class="btn btn-primary flex-grow-1">Add to Cart</a>
                                        <button class="btn btn-icon ms-2" onclick="toggleWishlist(<?php echo $product['id']; ?>)">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a href="<?php echo route('shop'); ?>" class="btn btn-primary">View All Products</a>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="partners-section py-5">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="section-title">Our Partners</h2>
                <p class="section-subtitle">We work with the best brands to provide quality products</p>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                    <img src="<?php echo asset('images/partner1.png'); ?>" alt="Partner Logo" class="img-fluid partner-logo">
                </div>
                <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                    <img src="<?php echo asset('images/partner2.png'); ?>" alt="Partner Logo" class="img-fluid partner-logo">
                </div>
                <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                    <img src="<?php echo asset('images/partner3.png'); ?>" alt="Partner Logo" class="img-fluid partner-logo">
                </div>
                <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                    <img src="<?php echo asset('images/partner4.png'); ?>" alt="Partner Logo" class="img-fluid partner-logo">
                </div>
                <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                    <img src="<?php echo asset('images/partner5.png'); ?>" alt="Partner Logo" class="img-fluid partner-logo">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-white">What Our Customers Say</h2>
                <p class="text-white-50">Real experiences from real pet lovers</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">My dog absolutely loves the premium food I ordered from PawShop! The delivery was fast and the quality is outstanding. Will definitely be ordering again!</p>
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <img src="<?php echo asset('images/customer1.jpg'); ?>" alt="Customer">
                            </div>
                            <div class="customer-info">
                                <h6 class="customer-name">Sarah Johnson</h6>
                                <p class="customer-type">Dog Owner</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">The cat tree I purchased exceeded my expectations! It's sturdy, well-made, and my cats absolutely love it. The price was great too!</p>
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <img src="<?php echo asset('images/customer2.jpg'); ?>" alt="Customer">
                            </div>
                            <div class="customer-info">
                                <h6 class="customer-name">Mike Peterson</h6>
                                <p class="customer-type">Cat Owner</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">The smart collar has been a game-changer for keeping track of my adventurous pup. The app is user-friendly and the customer service is excellent!</p>
                        <div class="d-flex align-items-center">
                            <div class="avatar">
                                <img src="<?php echo asset('images/customer3.jpg'); ?>" alt="Customer">
                            </div>
                            <div class="customer-info">
                                <h6 class="customer-name">Emily Rodriguez</h6>
                                <p class="customer-type">Dog Owner</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h3>Join Our Newsletter</h3>
                    <p class="text-muted mb-4">Subscribe to get special offers, free giveaways, and pet care tips!</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Your email address">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                    <small class="text-muted">We respect your privacy and will never share your information.</small>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4>About PawShop</h4>
                        <p>We are dedicated to providing the best products and services for your beloved pets.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4>Quick Links</h4>
                        <ul class="list-unstyled footer-links">
                            <li><a href="<?php echo route('shop'); ?>">Shop All</a></li>
                            <li><a href="<?php echo route('about'); ?>">About Us</a></li>
                            <li><a href="<?php echo route('contact'); ?>">Contact Us</a></li>
                            <li><a href="<?php echo route('faq'); ?>">FAQ</a></li>
                            <li><a href="<?php echo route('terms'); ?>">Terms & Conditions</a></li>
                            <li><a href="<?php echo route('privacy'); ?>">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-widget">
                        <h4>Pet Categories</h4>
                        <ul class="list-unstyled footer-links">
                            <li><a href="<?php echo route('category', 'dogs'); ?>">Dogs</a></li>
                            <li><a href="<?php echo route('category', 'cats'); ?>">Cats</a></li>
                            <li><a href="<?php echo route('category', 'birds'); ?>">Birds</a></li>
                            <li><a href="<?php echo route('category', 'fish'); ?>">Fish</a></li>
                            <li><a href="<?php echo route('category', 'small-pets'); ?>">Small Pets</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4>Contact Us</h4>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-map-marker-alt me-2"></i> 123 Pet Street, Petville, PV 12345</li>
                            <li><i class="fas fa-phone-alt me-2"></i> (123) 456-7890</li>
                            <li><i class="fas fa-envelope me-2"></i> info@pawshop.com</li>
                        </ul>
                        <div class="mt-3">
                            <h6 class="mb-3">Payment Options</h6>
                            <div class="d-flex">
                                <img src="<?php echo asset('images/visa.png'); ?>" alt="Visa" class="payment-icon">
                                <img src="<?php echo asset('images/mastercard.png'); ?>" alt="Mastercard" class="payment-icon">
                                <img src="<?php echo asset('images/paypal.png'); ?>" alt="PayPal" class="payment-icon">
                                <img src="<?php echo asset('images/amex.png'); ?>" alt="American Express" class="payment-icon">
                            </div>
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

    <!-- Back to top button -->
    <a href="#" class="back-to-top" id="backToTop"><i class="fas fa-arrow-up"></i></a>

    <!-- Toast notification for adding to cart -->
    <div class="toast-notification" id="cartNotification">
        <div class="toast-content">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Item added to cart!</strong>
                <p class="mb-0">Check your cart to complete your order.</p>
            </div>
        </div>
        <button type="button" class="btn-close" onclick="hideToast()"></button>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Back to top button functionality
        window.addEventListener('scroll', function() {
            const backToTopButton = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });

        // Toggle wishlist heart icon
        function toggleWishlist(productId) {
            // This would connect to a backend service in a real implementation
            const heartIcon = event.currentTarget.querySelector('i');
            
            if (heartIcon.classList.contains('far')) {
                heartIcon.classList.remove('far');
                heartIcon.classList.add('fas');
                heartIcon.style.color = '#ff3b30';
            } else {
                heartIcon.classList.remove('fas');
                heartIcon.classList.add('far');
                heartIcon.style.color = '';
            }
            
            event.preventDefault();
        }

        // Show toast notification
        function showToast() {
            const toast = document.getElementById('cartNotification');
            toast.classList.add('show');
            
            // Hide after 3 seconds
            setTimeout(function() {
                hideToast();
            }, 3000);
        }
        
        // Hide toast notification
        function hideToast() {
            const toast = document.getElementById('cartNotification');
            toast.classList.remove('show');
        }

        // Add event listeners to "Add to Cart" buttons
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartButtons = document.querySelectorAll('.btn-primary');
            
            addToCartButtons.forEach(button => {
                if (button.textContent.includes('Add to Cart')) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        showToast();
                    });
                }
            });
        });

        // Fade in elements on scroll
        const fadeInElements = document.querySelectorAll('.fade-in-up');

        const fadeInOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const fadeInObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, fadeInOptions);

        fadeInElements.forEach(element => {
            element.style.opacity = 0;
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'all 0.5s ease';
            fadeInObserver.observe(element);
        });
    </script>
</body>
</html>