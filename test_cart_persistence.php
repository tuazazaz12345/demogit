<?php
/**
 * 🔍 CART PERSISTENCE TEST SCRIPT
 * 
 * Chạy script này để kiểm tra xem:
 * 1. Database shopping_carts có tồn tại không
 * 2. ShoppingCartModel có hoạt động không
 * 3. Có lỗi gì không
 * 
 * Cách chạy: 
 *   - Bỏ vào MVC folder
 *   - Vào http://localhost/phpnangcao/MVC/test_cart_persistence.php
 */

session_start();

// Include necessary files
require_once 'app/config.php';
require_once 'app/DB.php';
require_once 'models/ShoppingCartModel.php';

echo "<h1>🔍 Cart Persistence Test Report</h1>";
echo "<hr>";

// ===== TEST 1: Database Connection =====
echo "<h2>Test 1: Database Connection</h2>";
try {
    $db = new DB();
    echo "✅ Database connection: OK<br>";
} catch (Exception $e) {
    echo "❌ Database connection: FAILED<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    exit;
}

// ===== TEST 2: shopping_carts Table Exists =====
echo "<h2>Test 2: shopping_carts Table</h2>";
try {
    $db = new DB();
    $sql = "SHOW TABLES LIKE 'shopping_carts'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "✅ Table shopping_carts exists<br>";
        
        // Check columns
        $sql = "DESCRIBE shopping_carts";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>" . $col['Field'] . "</td>";
            echo "<td>" . $col['Type'] . "</td>";
            echo "<td>" . $col['Null'] . "</td>";
            echo "<td>" . ($col['Key'] ?: 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "❌ Table shopping_carts does NOT exist<br>";
        echo "⚠️ Please run: CREATE_SHOPPING_CART_TABLE.sql<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking table: " . $e->getMessage() . "<br>";
}

// ===== TEST 3: ShoppingCartModel =====
echo "<h2>Test 3: ShoppingCartModel Functions</h2>";
try {
    $cartModel = new ShoppingCartModel();
    echo "✅ ShoppingCartModel loaded successfully<br>";
    
    // Check if methods exist
    $methods = ['saveCart', 'loadCart', 'clearCart', 'updateQuantity', 'removeItem'];
    foreach ($methods as $method) {
        if (method_exists($cartModel, $method)) {
            echo "✅ Method <code>$method()</code> exists<br>";
        } else {
            echo "❌ Method <code>$method()</code> NOT found<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Error loading ShoppingCartModel: " . $e->getMessage() . "<br>";
}

// ===== TEST 4: Test Save/Load with Sample Data =====
echo "<h2>Test 4: Save/Load Test with Sample User</h2>";

// Use a test user ID (change this to an existing user)
$testUserId = 1;

echo "<p><strong>Test User ID:</strong> $testUserId</p>";

try {
    $db = new DB();
    
    // Check if user exists
    $sql = "SELECT user_id, fullname FROM tbluser WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$testUserId]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ User found: " . $user['fullname'] . "<br>";
    } else {
        echo "⚠️ User ID $testUserId not found<br>";
        echo "<p>Please create a user first or use an existing user ID.</p>";
        echo "<hr>";
        exit;
    }
} catch (Exception $e) {
    echo "❌ Error checking user: " . $e->getMessage() . "<br>";
}

// Try to save sample cart
echo "<h3>Save Test:</h3>";
try {
    $sampleCart = [
        'P001' => [
            'qty' => 2,
            'masp' => 'P001',
            'tensp' => 'Test Product 1',
            'hinhanh' => 'test1.jpg',
            'giaxuat' => 100000,
            'khuyenmai' => 10,
            'from_promotion' => false
        ],
        'P002' => [
            'qty' => 1,
            'masp' => 'P002',
            'tensp' => 'Test Product 2',
            'hinhanh' => 'test2.jpg',
            'giaxuat' => 50000,
            'khuyenmai' => 5,
            'from_promotion' => false
        ]
    ];
    
    $cartModel = new ShoppingCartModel();
    $result = $cartModel->saveCart($testUserId, $sampleCart);
    
    if ($result) {
        echo "✅ Sample cart saved successfully<br>";
        echo "Items saved: " . count($sampleCart) . "<br>";
    } else {
        echo "❌ Failed to save sample cart<br>";
    }
} catch (Exception $e) {
    echo "❌ Error saving cart: " . $e->getMessage() . "<br>";
}

// Try to load cart back
echo "<h3>Load Test:</h3>";
try {
    $cartModel = new ShoppingCartModel();
    $loadedCart = $cartModel->loadCart($testUserId);
    
    if (!empty($loadedCart)) {
        echo "✅ Cart loaded successfully<br>";
        echo "Items loaded: " . count($loadedCart) . "<br>";
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Product ID</th><th>Name</th><th>Quantity</th><th>Price</th></tr>";
        foreach ($loadedCart as $masp => $item) {
            echo "<tr>";
            echo "<td>" . $masp . "</td>";
            echo "<td>" . $item['tensp'] . "</td>";
            echo "<td>" . $item['qty'] . "</td>";
            echo "<td>" . $item['giaxuat'] . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "⚠️ No cart found for user $testUserId<br>";
    }
} catch (Exception $e) {
    echo "❌ Error loading cart: " . $e->getMessage() . "<br>";
}

// ===== TEST 5: Database Contents =====
echo "<h2>Test 5: Current Database Contents</h2>";
try {
    $db = new DB();
    $sql = "SELECT * FROM shopping_carts WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$testUserId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($items) > 0) {
        echo "✅ Found " . count($items) . " items in shopping_carts for user $testUserId<br>";
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Product ID</th><th>Quantity</th><th>Price</th><th>Created At</th></tr>";
        foreach ($items as $item) {
            echo "<tr>";
            echo "<td>" . $item['id'] . "</td>";
            echo "<td>" . $item['product_id'] . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>" . $item['original_price'] . "</td>";
            echo "<td>" . $item['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "⚠️ No items found in shopping_carts for user $testUserId<br>";
    }
} catch (Exception $e) {
    echo "❌ Error reading database: " . $e->getMessage() . "<br>";
}

// ===== FINAL SUMMARY =====
echo "<h2>Summary</h2>";
echo "<p>All tests completed. Check results above.</p>";
echo "<p><strong>✅ If all tests passed:</strong> Cart persistence system is working correctly!</p>";
echo "<p><strong>❌ If some tests failed:</strong> Check the error messages above and fix the issues.</p>";

?>
