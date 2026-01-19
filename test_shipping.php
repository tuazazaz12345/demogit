<?php
/**
 * TEST SHIPPING METHODS - Kiểm tra tính năng vận chuyển
 * Truy cập: http://localhost/phpnangcao/MVC/test_shipping.php
 */

require_once 'models/BaseModel.php';
require_once 'models/ShippingMethodModel.php';

echo "<h1>🚚 Test Shipping Methods</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    h1 { color: #2c3e50; }
    .success { color: #27ae60; background: #e8f8f5; padding: 10px; border-left: 4px solid #27ae60; margin: 10px 0; }
    .error { color: #e74c3c; background: #fadbd8; padding: 10px; border-left: 4px solid #e74c3c; margin: 10px 0; }
    .info { color: #3498db; background: #ebf5fb; padding: 10px; border-left: 4px solid #3498db; margin: 10px 0; }
    table { width: 100%; border-collapse: collapse; background: white; margin: 20px 0; }
    th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
    th { background: #3498db; color: white; }
    .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
    .btn:hover { background: #2980b9; }
</style>";

$shippingModel = new ShippingMethodModel();

// ==================== TEST 1: Lấy tất cả phương thức vận chuyển ====================
echo "<h2>Test 1: Lấy Tất Cả Phương Thức Vận Chuyển</h2>";
try {
    $methods = $shippingModel->getActiveShippingMethods();
    
    if ($methods) {
        echo "<div class='success'>✓ Lấy được " . count($methods) . " phương thức vận chuyển</div>";
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Icon</th><th>Tên</th><th>Mô tả</th><th>Giá</th><th>Thời gian</th><th>Trạng thái</th></tr>";
        foreach ($methods as $method) {
            $price = $method['price'] == 0 ? 'MIỄN PHÍ' : number_format($method['price'], 0, ',', '.') . ' ₫';
            echo "<tr>";
            echo "<td>{$method['id']}</td>";
            echo "<td style='font-size: 24px;'>{$method['icon']}</td>";
            echo "<td><strong>{$method['name']}</strong></td>";
            echo "<td>{$method['description']}</td>";
            echo "<td style='color: #3498db; font-weight: bold;'>{$price}</td>";
            echo "<td>{$method['estimated_days']}</td>";
            echo "<td>" . ($method['is_active'] ? '✅ Active' : '❌ Inactive') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='error'>✗ Không có phương thức vận chuyển nào!</div>";
        echo "<div class='info'>💡 Hãy chạy file CREATE_SHIPPING_METHODS.sql để tạo dữ liệu mẫu</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Lỗi: " . $e->getMessage() . "</div>";
}

// ==================== TEST 2: Tính phí ship cho đơn hàng ====================
echo "<h2>Test 2: Tính Phí Ship Cho Đơn Hàng</h2>";

$testOrders = [
    ['total' => 200000, 'shipping_id' => 1],
    ['total' => 600000, 'shipping_id' => 1],
    ['total' => 600000, 'shipping_id' => 4], // Miễn phí ship
    ['total' => 300000, 'shipping_id' => 4], // Không đủ điều kiện
];

foreach ($testOrders as $index => $order) {
    echo "<h3>Đơn hàng #" . ($index + 1) . "</h3>";
    echo "<p>Tổng tiền sản phẩm: <strong>" . number_format($order['total'], 0, ',', '.') . " ₫</strong></p>";
    
    try {
        $result = $shippingModel->calculateShippingFee($order['shipping_id'], $order['total']);
        
        if ($result['success']) {
            echo "<div class='success'>";
            echo "✓ Tính phí ship thành công<br>";
            echo "Phương thức: <strong>{$result['method_name']}</strong><br>";
            echo "Phí ship: <strong>" . number_format($result['shipping_fee'], 0, ',', '.') . " ₫</strong><br>";
            echo "Thời gian giao: <strong>{$result['estimated_days']}</strong><br>";
            echo "Tổng thanh toán: <strong>" . number_format($order['total'] + $result['shipping_fee'], 0, ',', '.') . " ₫</strong>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "✗ {$result['message']}<br>";
            if (isset($result['min_order'])) {
                echo "Đơn hàng tối thiểu: " . number_format($result['min_order'], 0, ',', '.') . " ₫<br>";
                echo "Còn thiếu: " . number_format($result['min_order'] - $order['total'], 0, ',', '.') . " ₫";
            }
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>✗ Lỗi: " . $e->getMessage() . "</div>";
    }
}

// ==================== TEST 3: Kiểm tra điều kiện miễn phí ship ====================
echo "<h2>Test 3: Kiểm Tra Điều Kiện Miễn Phí Ship</h2>";

$testTotals = [100000, 300000, 500000, 700000, 1000000];

echo "<table>";
echo "<tr><th>Tổng đơn hàng</th><th>Đủ điều kiện?</th><th>Còn thiếu</th><th>Thông báo</th></tr>";

foreach ($testTotals as $total) {
    $check = $shippingModel->checkFreeShippingEligibility($total);
    
    echo "<tr>";
    echo "<td>" . number_format($total, 0, ',', '.') . " ₫</td>";
    echo "<td>" . ($check['eligible'] ? '✅ Có' : '❌ Không') . "</td>";
    echo "<td>" . ($check['remaining'] > 0 ? number_format($check['remaining'], 0, ',', '.') . ' ₫' : '-') . "</td>";
    echo "<td>" . $check['message'] . "</td>";
    echo "</tr>";
}

echo "</table>";

// ==================== TEST 4: Lấy phương thức mặc định ====================
echo "<h2>Test 4: Phương Thức Vận Chuyển Mặc Định</h2>";
try {
    $defaultMethod = $shippingModel->getDefaultShippingMethod();
    
    if ($defaultMethod) {
        echo "<div class='success'>";
        echo "✓ Phương thức mặc định: <strong>{$defaultMethod['name']}</strong><br>";
        echo "Giá: <strong>" . number_format($defaultMethod['price'], 0, ',', '.') . " ₫</strong><br>";
        echo "Thời gian: <strong>{$defaultMethod['estimated_days']}</strong>";
        echo "</div>";
    } else {
        echo "<div class='error'>✗ Không tìm thấy phương thức mặc định</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Lỗi: " . $e->getMessage() . "</div>";
}

// ==================== KIỂM TRA BẢNG ORDERS ====================
echo "<h2>Test 5: Kiểm Tra Cấu Trúc Bảng Orders</h2>";

try {
    $db = new BaseModel();
    $stmt = $db->select("SHOW COLUMNS FROM orders LIKE 'shipping%'");
    
    if ($stmt && count($stmt) >= 2) {
        echo "<div class='success'>✓ Bảng orders đã có cột shipping_method_id và shipping_fee</div>";
        
        echo "<table>";
        echo "<tr><th>Tên cột</th><th>Kiểu dữ liệu</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($stmt as $column) {
            echo "<tr>";
            echo "<td>{$column['Field']}</td>";
            echo "<td>{$column['Type']}</td>";
            echo "<td>{$column['Null']}</td>";
            echo "<td>{$column['Key']}</td>";
            echo "<td>{$column['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='error'>";
        echo "✗ Bảng orders chưa có cột shipping!<br>";
        echo "Hãy chạy lệnh ALTER TABLE trong file CREATE_SHIPPING_METHODS.sql";
        echo "</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Lỗi: " . $e->getMessage() . "</div>";
}

// ==================== LINKS ====================
echo "<h2>🔗 Links Hữu Ích</h2>";
echo "<a href='" . (defined('APP_URL') ? APP_URL : '/phpnangcao/MVC') . "/CartController/checkout' class='btn'>Đi đến trang Checkout</a>";
echo "<a href='SHIPPING_INSTALLATION_GUIDE.md' class='btn'>Xem hướng dẫn cài đặt</a>";

echo "<hr>";
echo "<p style='color: #7f8c8d; text-align: center;'>Test completed at " . date('Y-m-d H:i:s') . "</p>";
?>
