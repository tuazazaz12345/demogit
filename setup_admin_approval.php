<?php
/**
 * ADMIN APPROVAL SYSTEM - AUTO SETUP
 * Chạy file này để tự động tạo bảng
 */

// ==================== CẤU HÌNH ====================
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'website';

// ==================== KẾT NỐI DATABASE ====================
try {
    $conn = new mysqli($host, $user, $password, $database);
    
    if ($conn->connect_error) {
        die("❌ Kết nối thất bại: " . $conn->connect_error);
    }
    
    echo "<h2>✅ Kết nối database thành công</h2>";
    
} catch (Exception $e) {
    die("❌ Lỗi: " . $e->getMessage());
}

// ==================== CẤU TRÚC SQL ====================
$sqls = [
    // 1. Thêm cột vào tbladmin
    "ALTER TABLE tbladmin ADD COLUMN IF NOT EXISTS status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER created_at",
    "ALTER TABLE tbladmin ADD COLUMN IF NOT EXISTS approval_date DATETIME NULL AFTER status",
    "ALTER TABLE tbladmin ADD COLUMN IF NOT EXISTS approved_by INT NULL AFTER approval_date",
    
    // 2. Tạo bảng admin_approval_requests
    "CREATE TABLE IF NOT EXISTS admin_approval_requests (
        id INT PRIMARY KEY AUTO_INCREMENT,
        admin_id INT NOT NULL,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        fullname VARCHAR(255) NOT NULL,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        requested_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        approved_at DATETIME NULL,
        approved_by INT NULL,
        rejection_reason VARCHAR(500) NULL,
        
        FOREIGN KEY (admin_id) REFERENCES tbladmin(id) ON DELETE CASCADE,
        FOREIGN KEY (approved_by) REFERENCES tbladmin(id) ON DELETE SET NULL,
        INDEX (status),
        INDEX (requested_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // 3. Tạo bảng super_admins
    "CREATE TABLE IF NOT EXISTS super_admins (
        id INT PRIMARY KEY AUTO_INCREMENT,
        admin_id INT UNIQUE NOT NULL,
        role VARCHAR(50) DEFAULT 'super_admin',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (admin_id) REFERENCES tbladmin(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

// ==================== CHẠY SQL ====================
echo "<h3>Chạy SQL...</h3>";
echo "<ul>";

foreach ($sqls as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "<li>✅ Thành công</li>";
    } else {
        echo "<li>⚠️ " . $conn->error . "</li>";
    }
}

echo "</ul>";

// ==================== KIỂM TRA ====================
echo "<h3>Kiểm tra bảng...</h3>";

$tables = [
    'super_admins' => 'SELECT COUNT(*) as count FROM super_admins',
    'admin_approval_requests' => 'SELECT COUNT(*) as count FROM admin_approval_requests'
];

foreach ($tables as $table => $sql) {
    $result = $conn->query($sql);
    if ($result) {
        echo "<li>✅ Bảng <strong>$table</strong> tồn tại</li>";
    } else {
        echo "<li>❌ Bảng <strong>$table</strong> lỗi</li>";
    }
}

// ==================== TẠO SUPER ADMIN ====================
echo "<h3>Tạo Super Admin...</h3>";

$checkSuper = $conn->query("SELECT COUNT(*) as count FROM super_admins WHERE admin_id = 1");
if ($checkSuper) {
    $row = $checkSuper->fetch_assoc();
    if ($row['count'] == 0) {
        // Tạo super admin cho admin id = 1
        $insertSuper = $conn->query("INSERT INTO super_admins (admin_id, role) VALUES (1, 'super_admin')");
        if ($insertSuper) {
            echo "<li>✅ Super Admin (id=1) đã được tạo</li>";
        } else {
            echo "<li>⚠️ " . $conn->error . "</li>";
        }
    } else {
        echo "<li>ℹ️ Super Admin (id=1) đã tồn tại</li>";
    }
}

// ==================== HOÀN THÀNH ====================
echo "</ul>";
echo "<h3>✅ Thiết lập hoàn thành!</h3>";
echo "<p>Bây giờ bạn có thể truy cập: <a href='../index.php'>/AdminApprovalController/index</a></p>";

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Setup Admin Approval System</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h2, h3 { color: #333; }
        li { margin: 5px 0; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
</body>
</html>
