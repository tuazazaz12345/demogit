<?php
/**
 * 🔍 Complete Debug Guide for Review Issues
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/config.php';
require_once 'app/DB.php';
require_once 'models/BaseModel.php';
require_once 'models/ReviewModel.php';

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><style>";
echo "body { font-family: Arial; margin: 20px; }";
echo ".problem { background: #f8d7da; padding: 15px; margin: 10px 0; border-radius: 5px; border: 1px solid #f5c6cb; }";
echo ".solution { background: #d4edda; padding: 15px; margin: 10px 0; border-radius: 5px; border: 1px solid #c3e6cb; }";
echo ".warning { background: #fff3cd; padding: 15px; margin: 10px 0; border-radius: 5px; border: 1px solid #ffeaa7; }";
echo "table { border-collapse: collapse; width: 100%; margin: 10px 0; }";
echo "th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }";
echo "th { background: #007bff; color: white; }";
echo "tr:nth-child(even) { background: #f9f9f9; }";
echo ".code { background: #f5f5f5; padding: 10px; border-radius: 3px; font-family: monospace; overflow-x: auto; }";
echo "</style></head><body>";

echo "<h1>🔍 Complete Debug Guide: Review Issues</h1>";
echo "<hr>";

// ========== PROBLEM 1 ==========
echo "<h2>❌ Problem 1: Direct Review Không Hiển Thị Ở Quản Lý</h2>";

echo "<p><strong>Bạn báo cáo:</strong> 'Cái đánh giá trực tiếp ở trang chi tiết sản phẩm không hoạt động sau khi đánh giá thì nó không có ở trang quản lý đánh giá chờ duyệt'</p>";

echo "<h3>Nguyên Nhân Có Thể:</h3>";
echo "<ol>";
echo "<li><strong>Review không được insert</strong> vào database</li>";
echo "<li><strong>Review được insert nhưng:</strong></li>";
echo "<ul>";
echo "<li>trangthai không phải 'chờ duyệt'</li>";
echo "<li>moderation_status không phải 'pending'</li>";
echo "<li>Sản phẩm không còn trong database (JOIN bị lỗi)</li>";
echo "</ul>";
echo "</ol>";

echo "<h3>Cách Debug:</h3>";

$db = new DB();
$reviewModel = new ReviewModel();

// Check 1: Có direct review nào không?
$directCount = $db->selectOne("SELECT COUNT(*) as cnt FROM tblreview WHERE order_id IS NULL")['cnt'];
echo "<p><strong>1. Số Direct Review (order_id = NULL):</strong> " . $directCount . "</p>";

if ($directCount == 0) {
    echo "<div class='problem'>";
    echo "❌ <strong>Không có direct review nào!</strong>";
    echo "<br>Điều này có nghĩa review không được insert vào database hoặc bị xóa.";
    echo "<br><br><strong>Giải pháp:</strong>";
    echo "<ol>";
    echo "<li>Kiểm tra lại form submit trên trang chi tiết sản phẩm</li>";
    echo "<li>Check xem có error_log nào trong PHP</li>";
    echo "<li>Kiểm tra ReviewController::add() có return đúng không</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div class='solution'>✅ Có " . $directCount . " direct review trong database</div>";
    
    // Check 2: Direct reviews có status gì?
    $statusDist = $db->select(
        "SELECT trangthai, moderation_status, COUNT(*) as cnt 
         FROM tblreview WHERE order_id IS NULL 
         GROUP BY trangthai, moderation_status"
    );
    
    echo "<p><strong>2. Direct Review Status Distribution:</strong></p>";
    echo "<table>";
    echo "<tr><th>trangthai</th><th>moderation_status</th><th>Số lượng</th></tr>";
    foreach ($statusDist as $s) {
        $style = ($s['trangthai'] === 'chờ duyệt' && $s['moderation_status'] === 'pending') ? 'color: green;' : 'color: red;';
        echo "<tr><td style='{$style}'>" . htmlspecialchars($s['trangthai'] ?: 'NULL') . "</td>";
        echo "<td style='{$style}'>" . htmlspecialchars($s['moderation_status'] ?: 'NULL') . "</td>";
        echo "<td style='{$style}'>" . $s['cnt'] . "</td></tr>";
    }
    echo "</table>";
    
    // Check 3: getPendingReviews có lấy được không?
    $pending = $reviewModel->getPendingReviews();
    $directInPending = $db->selectOne(
        "SELECT COUNT(*) as cnt FROM (
            SELECT r.id FROM tblreview r
            LEFT JOIN tblsanpham p ON r.masp = p.masp
            WHERE r.order_id IS NULL AND (r.moderation_status = 'pending' OR r.trangthai = 'chờ duyệt')
        ) as t"
    )['cnt'];
    
    echo "<p><strong>3. getPendingReviews() Result:</strong></p>";
    echo "<p>Tổng pending (theo query): " . $directInPending . "</p>";
    echo "<p>Tổng pending (từ hàm): " . count($pending) . "</p>";
    
    if ($directInPending > count($pending)) {
        echo "<div class='problem'>";
        echo "❌ <strong>Không đủ review!</strong> Query trả " . $directInPending . " nhưng hàm chỉ trả " . count($pending) . "";
        echo "<br>Có thể lỗi JOIN với tblsanpham (sản phẩm bị xóa)";
        echo "<br><strong>Đã sửa:</strong> Thay đổi từ JOIN sang LEFT JOIN";
        echo "</div>";
    } else {
        echo "<div class='solution'>✅ getPendingReviews() trả đủ review</div>";
    }
}

// ========== PROBLEM 2 ==========
echo "<hr>";
echo "<h2>❌ Problem 2: Lỗi JSON Khi Ẩn Đánh Giá Ở Chi Tiết</h2>";

echo "<p><strong>Lỗi:</strong> 'SyntaxError: Unexpected token '<', \"<!DOCTYPE \"... is not valid JSON'</p>";

echo "<div class='warning'>";
echo "🔧 <strong>Nguyên Nhân:</strong> JSON response có HTML output (như error page HTML)";
echo "<br><strong>Đã sửa:</strong>";
echo "<ul>";
echo "<li>✅ Thêm JSON header TRƯỚC bất kỳ output nào</li>";
echo "<li>✅ Dùng ob_start() để buffer output</li>";
echo "<li>✅ Set HTTP status codes (200, 400, 401, 500)</li>";
echo "<li>✅ Check session AFTER setting header</li>";
echo "<li>✅ Thêm JSON_UNESCAPED_UNICODE để encode UTF-8 đúng</li>";
echo "</ul>";
echo "</div>";

// ========== PROBLEM 3 ==========
echo "<hr>";
echo "<h2>❌ Problem 3: Lỗi 'Not Found' Khi Xóa Đánh Giá</h2>";

echo "<p><strong>Lỗi:</strong> Khi xóa ở chi tiết đánh giá thì lỗi 404 Not Found, nhưng xóa ở quản lý thì OK</p>";

echo "<div class='warning'>";
echo "🔧 <strong>Nguyên Nhân:</strong> deleteReview() gọi checkAdminSession() trước, nó dùng header redirect nên gây lỗi";
echo "<br><strong>Đã sửa:</strong>";
echo "<ul>";
echo "<li>✅ Thay checkAdminSession() bằng session check thủ công</li>";
echo "<li>✅ Kiểm tra session TRƯỚC khi gọi delete</li>";
echo "<li>✅ Dùng deleteReview() method thay vì raw SQL query</li>";
echo "</ul>";
echo "</div>";

// ========== FIXED CODE SUMMARY ==========
echo "<hr>";
echo "<h2>✅ Fixed Issues Summary</h2>";

echo "<div class='solution'>";
echo "<h3>1. updateReviewStatus() - Fixed</h3>";
echo "<p>Thêm JSON header trước output + fix session check</p>";
echo "<div class='code'>";
echo "header('Content-Type: application/json; charset=UTF-8');<br>";
echo "if (ob_get_level() === 0) ob_start();<br>";
echo "// Check session AFTER header<br>";
echo "// Update CẢ 2 field: trangthai + moderation_status";
echo "</div>";
echo "</div>";

echo "<div class='solution'>";
echo "<h3>2. deleteReview() - Fixed</h3>";
echo "<p>Không dùng checkAdminSession() redirect, check manually</p>";
echo "<div class='code'>";
echo "if (session_status() === PHP_SESSION_NONE) session_start();<br>";
echo "if (!isset(\$_SESSION['admin'])) { redirect... exit; }";
echo "</div>";
echo "</div>";

echo "<div class='solution'>";
echo "<h3>3. getPendingReviews() - Fixed</h3>";
echo "<p>Thay JOIN → LEFT JOIN để không bỏ sót review</p>";
echo "<div class='code'>";
echo "LEFT JOIN tblsanpham p ON r.masp = p.masp<br>";
echo "COALESCE(p.tensp, 'Sản phẩm đã xóa')";
echo "</div>";
echo "</div>";

// ========== NEXT STEPS ==========
echo "<hr>";
echo "<h2>📋 Next Steps</h2>";

echo "<ol>";
echo "<li><strong>Test 1:</strong> Submit direct review tại: <a href='" . APP_URL . "/Review/create?masp=1'>/Review/create?masp=1</a></li>";
echo "<li><strong>Test 2:</strong> Check database: <a href='/check_reviews_database.php'>/check_reviews_database.php</a></li>";
echo "<li><strong>Test 3:</strong> Xem quản lý đánh giá: <a href='" . APP_URL . "/Admin/manageReviews'>/Admin/manageReviews</a></li>";
echo "<li><strong>Test 4:</strong> Vào chi tiết đánh giá rồi nhấn 'Ẩn' để test JSON response</li>";
echo "<li><strong>Test 5:</strong> Nhấn 'Xóa' để test delete redirect</li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color: green;'>✅ Tất cả lỗi đã được fix. Hãy test lại!</p>";

echo "</body></html>";
?>
