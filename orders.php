<?php
/**
 * PawShop Orders Dashboard
 * Pure PHP implementation without Laravel dependencies
 */

// Sample order data - in a real application, this would come from a database
$orders = [
    [
        'id' => 'ORD-2025-8742',
        'customer_name' => 'John Smith',
        'order_date' => 'Mar 15, 2025',
        'status' => 'pending',
        'total' => '129.99'
    ],
    [
        'id' => 'ORD-2025-8741',
        'customer_name' => 'Emma Johnson',
        'order_date' => 'Mar 14, 2025',
        'status' => 'shipped',
        'total' => '239.50'
    ],
    [
        'id' => 'ORD-2025-8740',
        'customer_name' => 'Michael Brown',
        'order_date' => 'Mar 13, 2025',
        'status' => 'delivered',
        'total' => '75.25'
    ],
    [
        'id' => 'ORD-2025-8739',
        'customer_name' => 'Sarah Davis',
        'order_date' => 'Mar 12, 2025',
        'status' => 'cancelled',
        'total' => '195.80'
    ]
];

// Sample order details for the view modal
$orderDetails = [
    'id' => 'ORD-2025-8742',
    'status' => 'In Transit',
    'order_date' => 'Mar 15, 2025',
    'payment_method' => 'Visa •••• 4242',
    'transaction_id' => 'TXN-5896-7412',
    'est_delivery' => 'Mar 18, 2025',
    'customer_name' => 'John Smith',
    'customer_email' => 'john.smith@example.com',
    'customer_phone' => '(555) 123-4567',
    'shipping_address' => '123 Main St, Apt 4B, New York, NY 10001',
    'courier' => 'FedEx',
    'tracking_number' => 'FDX-12345-6789',
    'shipping_method' => 'Standard Shipping',
    'items' => [
        [
            'product_name' => 'Premium Headphones',
            'quantity' => 1,
            'price' => 89.99
        ],
        [
            'product_name' => 'Wireless Charger',
            'quantity' => 2,
            'price' => 19.99
        ]
    ],
    'subtotal' => '129.97',
    'tax' => '0.00',
    'shipping_fee' => '0.00',
    'total' => '129.97',
    'timeline' => [
        [
            'timestamp' => 'Mar 15, 2025 18:42',
            'description' => 'Order placed',
            'user' => 'System'
        ],
        [
            'timestamp' => 'Mar 15, 2025 19:05',
            'description' => 'Payment confirmed',
            'user' => 'System'
        ],
        [
            'timestamp' => 'Mar 16, 2025 09:12',
            'description' => 'Order shipped',
            'user' => 'Admin'
        ]
    ]
];

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple form processing logic could go here
    // For a real application, you'd process forms and redirect
    
    // Example:
    // if (isset($_POST['status'])) {
    //     // Update order status logic
    // }
}

// Helper function to display status badge
function getStatusBadge($status) {
    switch($status) {
        case 'pending':
            return '<span class="status-badge status-pending">Pending</span>';
        case 'shipped':
            return '<span class="status-badge status-shipped">Shipped</span>';
        case 'delivered':
            return '<span class="status-badge status-delivered">Delivered</span>';
        case 'cancelled':
            return '<span class="status-badge status-cancelled">Cancelled</span>';
        default:
            return '<span class="status-badge">' . ucfirst($status) . '</span>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawShop Orders Dashboard</title>
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
            <div class="page-header">
                <h1 class="h3">Orders Dashboard</h1>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2"></i>Export as CSV</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Export as Excel</a></li>
                    </ul>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Search by Order ID, Customer, or Tracking Number">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select">
                            <option selected>All Statuses</option>
                            <option>Pending</option>
                            <option>Processing</option>
                            <option>Shipped</option>
                            <option>Delivered</option>
                            <option>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select">
                            <option selected>All Payment Methods</option>
                            <option>Credit Card</option>
                            <option>PayPal</option>
                            <option>Bank Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar text-muted"></i>
                            </span>
                            <input type="date" class="form-control" value="2025-03-18">
                        </div>
                    </div>
                    <div class="col-md-1 text-center">
                        <span class="fw-bold">to</span>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar text-muted"></i>
                            </span>
                            <input type="date" class="form-control" value="2025-03-18">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="orders-table">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= htmlspecialchars($order['order_date']) ?></td>
                            <td><?= getStatusBadge($order['status']) ?></td>
                            <td>₱<?= htmlspecialchars($order['total']) ?></td>
                            <td>
                                <button class="btn action-btn view-btn" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>
                                <button class="btn action-btn update-btn" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                    <i class="fas fa-edit me-1"></i> Update
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination-container">
                    <div>
                        <span class="text-muted">Showing 1 to 4 of 24 results</span>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination m-0">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link active" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <li class="page-item"><a class="page-link" href="#">6</a></li>
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

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="orderDetailsModalLabel">
                        <i class="fas fa-shopping-bag me-2 text-primary"></i>
                        Order #<?= htmlspecialchars($orderDetails['id']) ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Order Status Banner -->
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-truck me-2"></i>
                        <div>
                            <strong>Status:</strong> <?= htmlspecialchars($orderDetails['status']) ?>
                            <a href="#" class="ms-3 text-decoration-none" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="fas fa-edit"></i> Update
                            </a>
                        </div>
                    </div>
                    
                    <!-- Order Summary Card -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Order Summary</h6>
                            <span class="badge bg-success">Paid</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <small class="text-muted d-block">Order Date</small>
                                    <span><?= htmlspecialchars($orderDetails['order_date']) ?></span>
                                </div>
                                <div class="col-md-3 col-6">
                                    <small class="text-muted d-block">Payment Method</small>
                                    <span><?= htmlspecialchars($orderDetails['payment_method']) ?></span>
                                </div>
                                <div class="col-md-3 col-6">
                                    <small class="text-muted d-block">Transaction ID</small>
                                    <span><?= htmlspecialchars($orderDetails['transaction_id']) ?></span>
                                </div>
                                <div class="col-md-3 col-6">
                                    <small class="text-muted d-block">Est. Delivery</small>
                                    <span><?= htmlspecialchars($orderDetails['est_delivery']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Customer Information Card -->
                        <div class="col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white">
                                    <h6 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Customer Details</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong><?= htmlspecialchars($orderDetails['customer_name']) ?></strong></p>
                                    <p class="mb-1 text-muted"><?= htmlspecialchars($orderDetails['customer_email']) ?></p>
                                    <p class="mb-1"><?= htmlspecialchars($orderDetails['customer_phone']) ?></p>
                                    <hr>
                                    <small class="text-muted">Shipping Address</small>
                                    <p class="mb-0"><?= htmlspecialchars($orderDetails['shipping_address']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shipping Information Card -->
                        <div class="col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white">
                                    <h6 class="mb-0"><i class="fas fa-truck me-2 text-primary"></i>Shipping Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Courier</span>
                                        <strong><?= htmlspecialchars($orderDetails['courier']) ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tracking Number</span>
                                        <strong>
                                            <a href="#" class="text-decoration-none"><?= htmlspecialchars($orderDetails['tracking_number']) ?></a>
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping Method</span>
                                        <strong><?= htmlspecialchars($orderDetails['shipping_method']) ?></strong>
                                    </div>
                                    <div class="progress mt-3" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <small>Shipped</small>
                                        <small>In Transit</small>
                                        <small class="text-muted">Delivered</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Items Card -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="fas fa-box-open me-2 text-primary"></i>Order Items</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderDetails['items'] as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($item['quantity']) ?></td>
                                            <td class="text-end">₱<?= number_format($item['price'], 2) ?></td>
                                            <td class="text-end">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal</span>
                                        <span>₱<?= htmlspecialchars($orderDetails['subtotal']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tax</span>
                                        <span>₱<?= htmlspecialchars($orderDetails['tax']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping</span>
                                        <span>₱<?= htmlspecialchars($orderDetails['shipping_fee']) ?></span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <strong>Total</strong>
                                        <strong class="text-primary">₱<?= htmlspecialchars($orderDetails['total']) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                     <!-- Order Timeline -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Order Timeline</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($order->timeline ?? [] as $event)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-secondary rounded-pill me-2">{{ $event->timestamp }}</span>
                                        {{ $event->description }}
                                    </div>
                                    <span class="text-muted">{{ $event->user }}</span>
                                </li>
                            @empty
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-secondary rounded-pill me-2">Mar 15, 2025 18:42</span>
                                        Order placed
                                    </div>
                                    <span class="text-muted">System</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-secondary rounded-pill me-2">Mar 15, 2025 19:05</span>
                                        Payment confirmed
                                    </div>
                                    <span class="text-muted">System</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-secondary rounded-pill me-2">Mar 16, 2025 09:12</span>
                                        Order shipped
                                    </div>
                                    <span class="text-muted">Admin</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="printOrder()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">
                        <i class="fas fa-bell me-1"></i> Notify
                    </button>
                </div>
                <div class="btn-group ms-2" role="group">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#refundModal">
                        <i class="fas fa-undo me-1"></i> Issue Refund
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.update-status', $order->id ?? 1) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="orderStatus" class="form-label">Order Status</label>
                        <select class="form-select" id="orderStatus" name="status" required>
                            <option value="" selected disabled>Select Status</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="statusNote" class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="statusNote" name="note" rows="2" placeholder="Add any relevant details"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1" aria-labelledby="sendNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendNotificationModalLabel">Send Customer Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.send-notification', $order->id ?? 1) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notificationType" class="form-label">Notification Type</label>
                        <select class="form-select" id="notificationType" name="type" required>
                            <option value="" selected disabled>Select Type</option>
                            <option value="status_update">Status Update</option>
                            <option value="shipping_delay">Shipping Delay</option>
                            <option value="delivery_confirmation">Delivery Confirmation</option>
                            <option value="custom">Custom Message</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notificationMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="notificationMessage" name="message" rows="3" required></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="sendEmail" name="send_email" checked>
                        <label class="form-check-label" for="sendEmail">
                            Send via Email
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="sendSMS" name="send_sms">
                        <label class="form-check-label" for="sendSMS">
                            Send via SMS
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send Notification</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="refundModalLabel">Issue Refund</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.refund', $order->id ?? 1) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        You are about to issue a refund for order #ORD-2025-8742
                    </div>
                    
                    <div class="mb-3">
                        <label for="refundAmount" class="form-label">Refund Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="refundAmount" name="amount" value="129.97" step="0.01" min="0" max="129.97" required>
                        </div>
                        <div class="form-text">Maximum refund amount: ₱129.97</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="refundReason" class="form-label">Reason for Refund</label>
                        <select class="form-select" id="refundReason" name="reason" required>
                            <option value="" selected disabled>Select Reason</option>
                            <option value="customer_request">Customer Request</option>
                            <option value="damaged_product">Damaged Product</option>
                            <option value="wrong_item">Wrong Item Shipped</option>
                            <option value="late_delivery">Late Delivery</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="refundNote" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="refundNote" name="note" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Process Refund</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
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