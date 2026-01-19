<?php
/**
 * Test: Submit Direct Review
 * Dùng để test submit review trực tiếp ở trang chi tiết
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once 'app/config.php';

echo "<h1>📝 Test Direct Review Submission</h1>";
echo "<hr>";

// Simulate user login
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'fullname' => 'Test User',
        'email' => 'test@example.com'
    ];
    echo "<p>✅ Simulated login for: Test User</p>";
}

// Get a product to review
require_once 'app/DB.php';
$db = new DB();
$products = $db->select("SELECT masp, tensp FROM tblsanpham LIMIT 5");

echo "<h2>1. Chọn Sản Phẩm Để Đánh Giá</h2>";
echo "<form method='POST'>";
echo "<select name='product_id' class='form-control' required>";
echo "<option>-- Chọn sản phẩm --</option>";
foreach ($products as $p) {
    echo "<option value='" . htmlspecialchars($p['masp']) . "'>" . htmlspecialchars($p['tensp']) . "</option>";
}
echo "</select>";
echo "<br><br>";
echo "<button type='submit' class='btn btn-primary'>Đánh Giá Sản Phẩm Này</button>";
echo "</form>";

// If product selected, show form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $masp = $_POST['product_id'];
    
    echo "<h2>2. Form Đánh Giá</h2>";
    echo "<form method='POST' action='/Review/add'>";
    echo "<input type='hidden' name='masp' value='" . htmlspecialchars($masp) . "'>";
    echo "<input type='hidden' name='sosao' value='5'>";
    echo "<textarea name='noidung' required minlength='10'>Đây là một sản phẩm rất tốt! Tôi rất hài lòng với chất lượng sản phẩm này.</textarea>";
    echo "<br><br>";
    echo "<button type='submit' class='btn btn-success'>Gửi Đánh Giá</button>";
    echo "</form>";
    
    echo "<h2>3. Check Database Sau Khi Submit</h2>";
    echo "<p>Sau khi submit, kiểm tra:</p>";
    echo "<code>SELECT * FROM tblreview WHERE masp = '" . htmlspecialchars($masp) . "' ORDER BY ngaygui DESC LIMIT 5;</code>";
    echo "<br><br>";
    
    // Show last 5 reviews for this product
    $reviews = $db->select("SELECT id, order_id, trangthai, moderation_status FROM tblreview WHERE masp = ? ORDER BY ngaygui DESC LIMIT 5", [$masp]);
    
    echo "<h3>Review Gần Đây Cho Sản Phẩm Này:</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>order_id</th><th>trangthai</th><th>moderation_status</th></tr>";
    foreach ($reviews as $r) {
        echo "<tr>";
        echo "<td>" . $r['id'] . "</td>";
        echo "<td>" . ($r['order_id'] ?: 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($r['trangthai']) . "</td>";
        echo "<td>" . htmlspecialchars($r['moderation_status']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";
echo "<p><strong>Lưu ý:</strong> Nếu review không thấy ở bảng trên, có nghĩa nó không được insert vào database.</p>";
?>
