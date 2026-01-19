<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load the necessary files
require_once 'app/config.php';
require_once 'app/DB.php';
require_once 'models/BaseModel.php';
require_once 'models/OrderModel.php';

// Initialize OrderModel
$orderModel = new OrderModel();

// Test: Get the latest order
echo "<h2>Testing Order Data Retrieval</h2>";

// Get all orders
$allOrders = $orderModel->getAllOrders();
echo "<p><strong>Total orders in database:</strong> " . count($allOrders) . "</p>";

if (!empty($allOrders)) {
    $latestOrder = $allOrders[0];
    echo "<h3>Latest Order (ID: " . $latestOrder['id'] . ")</h3>";
    echo "<pre>";
    print_r($latestOrder);
    echo "</pre>";
    
    // Test getting order by ID
    $orderId = $latestOrder['id'];
    echo "<h3>Getting Order by ID: " . $orderId . "</h3>";
    $order = $orderModel->getOrderById($orderId);
    echo "<pre>";
    print_r($order);
    echo "</pre>";
    
    // Test getting order details
    echo "<h3>Getting Order Details for Order ID: " . $orderId . "</h3>";
    $details = $orderModel->getOrderDetails($orderId);
    echo "<pre>";
    print_r($details);
    echo "</pre>";
    
    // Test getting order by code
    if (isset($order['order_code'])) {
        echo "<h3>Getting Order by Code: " . $order['order_code'] . "</h3>";
        $orderByCode = $orderModel->getOrderByCode($order['order_code']);
        echo "<pre>";
        print_r($orderByCode);
        echo "</pre>";
    }
}

// Test SMTP connection
echo "<hr>";
echo "<h2>Testing SMTP Connection</h2>";
try {
    require_once 'app/EmailService.php';
    $emailService = new EmailService();
    echo "<p><strong style='color: green;'>EmailService initialized successfully</strong></p>";
    echo "<p>SMTP credentials configured (check your terminal for detailed logs)</p>";
} catch (Exception $e) {
    echo "<p><strong style='color: red;'>Error initializing EmailService:</strong> " . $e->getMessage() . "</p>";
}
?>
