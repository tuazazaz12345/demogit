<?php

/**
 * Helper function để kiểm tra quyền hạn trong view
 */
function canAccess($resource, $action, $db = null, $adminId = null) {
    if (!isset($_SESSION['login'])) {
        return false;
    }

    if (!$adminId) {
        $adminId = $_SESSION['login'];
    }

    if (!$db) {
        global $connection;
        if (!isset($connection)) {
            require_once __DIR__ . '/config.php';
        }
        $db = $connection;
    }

    require_once __DIR__ . '/../models/RoleModel.php';
    $roleModel = new RoleModel($db);
    return $roleModel->hasPermission($adminId, $resource, $action);
}

/**
 * Hiển thị phần tử HTML nếu có quyền
 */
function ifCanAccess($resource, $action, $content, $db = null, $adminId = null) {
    if (canAccess($resource, $action, $db, $adminId)) {
        echo $content;
    }
}

/**
 * Ghi log hành động
 */
function logAction($action, $details, $db = null) {
    try {
        if (!$db) {
            global $connection;
            $db = $connection;
        }

        $adminId = isset($_SESSION['login']) ? $_SESSION['login'] : null;
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        // Tạo bảng log nếu chưa tồn tại
        $sql = "CREATE TABLE IF NOT EXISTS admin_logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            admin_id INT,
            action VARCHAR(100),
            details TEXT,
            ip_address VARCHAR(50),
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (admin_id) REFERENCES tbluser(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->query($sql);
        
        // Thêm log
        $sql = "INSERT INTO admin_logs (admin_id, action, details, ip_address) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("isss", $adminId, $action, $details, $ip);
        $stmt->execute();
    } catch (Exception $e) {
        error_log("Error in logAction: " . $e->getMessage());
    }
}

?>
