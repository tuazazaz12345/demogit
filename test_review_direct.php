<?php
// Kiểm tra ReviewController::add() có nhận POST không
session_start();

// Tạo session user nếu chưa có
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 999,
        'email' => 'testuserreview@example.com',
        'fullname' => 'Test User Review'
    ];
}

echo "=== Testing Review Submission ===<br>";
echo "Session User: " . $_SESSION['user']['email'] . "<br><br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    // Load ReviewController
    require_once 'app/Controller.php';
    require_once 'controllers/ReviewController.php';
    
    // Create instance and call add()
    $controller = new ReviewController();
    
    // Set $_POST and call add
    echo "<h3>Calling ReviewController::add()...</h3>";
    
    // The add() method will handle redirect, so we need to capture it
    ob_start();
    $controller->add();
    $output = ob_get_clean();
    
    echo "Output: " . $output;
    
} else {
    // Display form
    echo "<h3>Submit Review Test Form</h3>";
    echo "<form method='POST'>";
    echo "Product ID: <input type='text' name='masp' value='SP001'><br>";
    echo "Stars (1-5): <input type='number' name='sosao' min='1' max='5' value='5'><br>";
    echo "Review: <textarea name='noidung' rows='4' cols='50'>This is a test review with minimum 10 characters required for validation</textarea><br>";
    echo "Order ID (optional): <input type='text' name='order_id'><br>";
    echo "<button type='submit'>Test Submit</button>";
    echo "</form>";
}
?>
