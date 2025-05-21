<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function asset($path) {
    return $path; // In a real app, you'd use your root URL
}

function route($name, $params = null) {
    // Simple router function - in a real app you'd have proper routing
    $routes = [
        'home' => 'home.php',
        'shop' => 'shop.php',
        'about' => 'about.php',
        'contact' => 'contact.php',
        'favorites' => 'favorites.php',
        'account' => 'account.php',
        'cart' => 'cart.php',
        'checkout' => 'checkout.php',
        'cart.edit' => 'cart-edit.php?id=' . (is_numeric($params) ? $params : ''),
        'cart.remove' => 'cart-remove.php?id=' . (is_numeric($params) ? $params : ''),
        'wishlist.add' => 'wishlist-add.php?id=' . (is_numeric($params) ? $params : '')
    ];
    
    return isset($routes[$name]) ? $routes[$name] : '#';
}

function number_format_custom($number, $decimals = 2) {
    return number_format($number, $decimals);
}

// Get current route
$current_route = basename($_SERVER['PHP_SELF']);

// Cart data from session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_total = isset($_SESSION['cart_total']) ? $_SESSION['cart_total'] : 0;
$discount = isset($_SESSION['discount']) ? $_SESSION['discount'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
    <title>Cart - PawShop</title>
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
                <a class="navbar-brand" href="<?php echo route('home.php'); ?>">
                    <img src="<?php echo asset('images/pawshop-logo.png'); ?>" alt="PawShop Logo" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_route == 'home.php' ? 'active' : ''; ?>" href="<?php echo route('home'); ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_route == 'shop.php' ? 'active' : ''; ?>" href="<?php echo route('shop'); ?>">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_route == 'about.php' ? 'active' : ''; ?>" href="<?php echo route('about'); ?>">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_route == 'contact.php' ? 'active' : ''; ?>" href="<?php echo route('contact'); ?>">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Search Component (simplified) -->
                    <div class="position-relative">
                        <form action="search.php" method="GET">
                            <input type="text" name="q" class="form-control form-control-sm rounded-pill" placeholder="Search...">
                        </form>
                    </div>
                    <a href="<?php echo route('favorites'); ?>" class="nav-icon text-dark ms-3"><i class="fas fa-heart"></i></a>
                    <a href="<?php echo route('account'); ?>" class="nav-icon text-dark ms-3"><i class="fas fa-user"></i></a>
                    <a href="<?php echo route('cart'); ?>" class="nav-icon text-dark position-relative ms-3">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if(!empty($cart)): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                <?php echo count($cart); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container my-5">
            <h1 class="mb-4">Your Cart</h1>

            <?php if(!empty($cart)): ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($cart as $id => $item): ?>
                                    <tr class="border-top">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo asset($item['image']); ?>" alt="<?php echo $item['name']; ?>" height="80" class="me-3">
                                                <div>
                                                    <h6 class="mb-0"><?php echo $item['name']; ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo isset($item['size']) ? $item['size'] : '2 kg'; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartItem(<?php echo $id; ?>, -1)">-</button>
                                                <span class="mx-2"><?php echo $item['quantity']; ?></span>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartItem(<?php echo $id; ?>, 1)">+</button>
                                            </div>
                                        </td>
                                        <td>₱ <?php echo number_format_custom($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td colspan="4">
                                            <div class="d-flex gap-3 my-2">
                                                <a href="<?php echo route('cart.edit', $id); ?>" class="text-decoration-none text-secondary">Edit</a>
                                                <a href="<?php echo route('cart.remove', $id); ?>" class="text-decoration-none text-secondary">Remove</a>
                                                <a href="<?php echo route('wishlist.add', $id); ?>" class="text-decoration-none text-primary">Move to wishlist</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <p class="mb-0"><?php echo count($cart); ?> item(s)</p>
                                <p class="text-primary mb-0">₱ <?php echo number_format_custom($cart_total, 2); ?></p>
                            </div>
                            <a href="<?php echo route('shop'); ?>" class="btn btn-link text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Order Summary</h5>
                                
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Promo code" aria-label="Promo code">
                                        <button class="btn btn-dark" type="button">Submit</button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping Cost</span>
                                    <span>TBD</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discount</span>
                                    <span>-₱<?php echo number_format_custom($discount, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Tax</span>
                                    <span>TBD</span>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between mb-3">
                                    <h5>Estimated Total:</h5>
                                    <h5>₱ <?php echo number_format_custom($cart_total, 2); ?></h5>
                                </div>

                                <div class="text-center mb-3">
                                    <small class="text-muted">or 4 interest-free payments of ₱<?php echo number_format_custom($cart_total / 4, 2); ?> with <span class="text-primary fw-bold">Paypal</span></small>
                                </div>

                                <?php if($cart_total < 1000): ?>
                                    <div class="text-center mb-3">
                                        <small class="text-danger">You're ₱<?php echo number_format_custom(1000 - $cart_total, 2); ?> away from free shipping!</small>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center mb-3">
                                        <small class="text-success">You qualify for free shipping!</small>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php echo route('checkout'); ?>" class="btn btn-primary w-100 py-2">Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
                    <a href="<?php echo route('shop'); ?>" class="btn btn-primary mt-3">
                        Continue Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer would go here -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateCartItem(id, change) {
            fetch(`cart-update.php?id=${id}&change=${change}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }
    </script>
</body>
</html>