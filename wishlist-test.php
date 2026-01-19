<?php
// Test file - kiểm tra session và database

session_start();

echo "<h2>📊 Session Info</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['user'])) {
    echo "<p style='color: green;'>✅ User đã login: " . $_SESSION['user']['email'] . "</p>";
} else {
    echo "<p style='color: red;'>❌ User chưa login</p>";
}

// Kiểm tra database
require_once 'app/DB.php';
$db = new DB();

echo "<h2>📂 Database Tables</h2>";
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('wishlist', $tables)) {
        echo "<p style='color: green;'>✅ Table 'wishlist' tồn tại</p>";
        
        // Xem structure
        echo "<h3>Wishlist table structure:</h3>";
        $columns = $pdo->query("DESCRIBE wishlist")->fetchAll();
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
        
        // Xem dữ liệu
        echo "<h3>Wishlist data:</h3>";
        if (isset($_SESSION['user'])) {
            $data = $pdo->prepare("SELECT * FROM wishlist WHERE user_email = ?")->execute([$_SESSION['user']['email']]);
            $result = $pdo->prepare("SELECT * FROM wishlist WHERE user_email = ?")->fetchAll();
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        }
    } else {
        echo "<p style='color: red;'>❌ Table 'wishlist' KHÔNG tồn tại</p>";
        echo "<p>Cần chạy SQL để tạo bảng:</p>";
        echo "<pre>
CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) NOT NULL,
    masp VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_user_product (user_email, masp),
    KEY idx_email (user_email),
    KEY idx_masp (masp),
    
    FOREIGN KEY (user_email) REFERENCES users(email) ON DELETE CASCADE,
    FOREIGN KEY (masp) REFERENCES tblsanpham(masp) ON DELETE CASCADE
);
        </pre>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<h2>🧪 Test API</h2>";
echo "<p>Mở DevTools (F12) → Network tab → Click nút Yêu thích để xem request</p>";
