<?php
// Test cart save/load functionality
error_log("=== TEST CART SAVE/LOAD START ===");

define('APP_URL', '/phpnangcao/MVC');

require_once 'app/DB.php';
require_once 'models/BaseModel.php';
require_once 'models/ShoppingCartModel.php';

try {
    $cartModel = new ShoppingCartModel();
    
    // Test data
    $userId = 1; // Change this to a valid user_id from your database
    $testCart = [
        '7' => [
            'qty' => 2,
            'masp' => '7',
            'tensp' => 'Sách Người Hùng Ý Tưởng',
            'hinhanh' => 'test.jpg',
            'giaxuat' => 200000,
            'khuyenmai' => 10,
            'promotional_price' => null,
            'from_promotion' => false
        ],
        '8' => [
            'qty' => 1,
            'masp' => '8',
            'tensp' => 'Sách Test',
            'hinhanh' => 'test2.jpg',
            'giaxuat' => 300000,
            'khuyenmai' => 0,
            'promotional_price' => null,
            'from_promotion' => false
        ]
    ];
    
    // Save cart
    error_log("TEST: Saving cart for user_id: " . $userId);
    $saveResult = $cartModel->saveCart($userId, $testCart);
    error_log("TEST: Save result: " . ($saveResult ? 'SUCCESS' : 'FAILED'));
    
    // Load cart
    error_log("TEST: Loading cart for user_id: " . $userId);
    $loadedCart = $cartModel->loadCart($userId);
    error_log("TEST: Loaded " . count($loadedCart) . " items");
    error_log("TEST: Loaded cart: " . json_encode($loadedCart));
    
    // Compare
    if (count($loadedCart) === count($testCart)) {
        error_log("TEST: ✅ PASS - Cart count matches");
    } else {
        error_log("TEST: ❌ FAIL - Cart count mismatch. Expected: " . count($testCart) . ", Got: " . count($loadedCart));
    }
    
} catch (Exception $e) {
    error_log("TEST ERROR: " . $e->getMessage());
    error_log("TEST STACK: " . $e->getTraceAsString());
}

error_log("=== TEST CART SAVE/LOAD END ===");
?>
