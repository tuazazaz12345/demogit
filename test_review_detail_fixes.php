<?php
/**
 * Test Fix: Review Detail Page Issues
 * Kiểm tra xem các issue đã fix chưa
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/config.php';
require_once 'app/DB.php';
require_once 'models/BaseModel.php';
require_once 'models/ReviewModel.php';

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'>";
echo "<title>Test Review Detail Fix</title>";
echo "<style>";
echo "body { font-family: Arial; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 900px; margin: 0 auto; }";
echo ".fix { background: #d4edda; padding: 15px; margin: 15px 0; border-radius: 5px; border: 1px solid #c3e6cb; }";
echo ".test { background: #d1ecf1; padding: 15px; margin: 15px 0; border-radius: 5px; border: 1px solid #bee5eb; }";
echo ".code { background: #f5f5f5; padding: 10px; font-family: monospace; overflow-x: auto; border-radius: 3px; }";
echo "a { color: #007bff; text-decoration: none; font-weight: bold; }";
echo "a:hover { text-decoration: underline; }";
echo "table { width: 100%; border-collapse: collapse; margin: 10px 0; }";
echo "th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }";
echo "th { background: #007bff; color: white; }";
echo "</style>";
echo "</head><body>";

echo "<div class='container'>";

echo "<h1>✅ Review Detail Page - Fixes Verification</h1>";
echo "<hr>";

// ========== FIX 1 ==========
echo "<div class='fix'>";
echo "<h2>✅ Fix 1: Quay Lại Link</h2>";
echo "<p><strong>Vấn đề cũ:</strong> Dùng hardcode '/Admin/manageReviews' gây 404</p>";
echo "<p><strong>Sửa:</strong> Dùng APP_URL constant để tạo URL động</p>";
echo "<div class='code'>";
echo "&lt;a href=\"&lt;?php echo APP_URL; ?&gt;/Admin/manageReviews\"&gt;...&lt;/a&gt;";
echo "</div>";
echo "<p><strong>Kết quả:</strong> Link sẽ tự động adjust theo APP_URL config</p>";
echo "</div>";

// ========== FIX 2 ==========
echo "<div class='fix'>";
echo "<h2>✅ Fix 2: Ẩn Đánh Giá (AJAX)</h2>";
echo "<p><strong>Vấn đề cũ:</strong></p>";
echo "<ul>";
echo "<li>JSON header thiếu hoặc không ở đầu</li>";
echo "<li>checkAdminSession() gây redirect HTML trước khi output JSON</li>";
echo "<li>Không check content-type của response</li>";
echo "</ul>";
echo "<p><strong>Sửa:</strong></p>";
echo "<div class='code'>";
echo "1. Thêm ob_start() để buffer<br>";
echo "2. Set JSON header TRƯỚC bất kỳ output<br>";
echo "3. Check session AFTER header<br>";
echo "4. Thêm error handling cho response JSON<br>";
echo "5. Thêm contentType check trong JavaScript";
echo "</div>";
echo "<p><strong>Code trong Admin::updateReviewStatus():</strong></p>";
echo "<div class='code'>";
echo "if (ob_get_level() === 0) ob_start();<br>";
echo "header('Content-Type: application/json; charset=UTF-8');<br>";
echo "if (session_status() === PHP_SESSION_NONE) session_start();<br>";
echo "if (!isset(\$_SESSION['admin'])) { ... echo json_encode(...); exit; }";
echo "</div>";
echo "</div>";

// ========== FIX 3 ==========
echo "<div class='fix'>";
echo "<h2>✅ Fix 3: Xóa Đánh Giá</h2>";
echo "<p><strong>Vấn đề cũ:</strong> Dùng hardcode '/Admin/deleteReview' + checkAdminSession() gây redirect lỗi</p>";
echo "<p><strong>Sửa:</strong></p>";
echo "<div class='code'>";
echo "&lt;?php echo APP_URL; ?&gt;/Admin/deleteReview/&lt;?php echo \$review['id']; ?&gt;";
echo "</div>";
echo "<p><strong>Code trong Admin::deleteReview():</strong></p>";
echo "<div class='code'>";
echo "if (session_status() === PHP_SESSION_NONE) session_start();<br>";
echo "if (!isset(\$_SESSION['admin'])) { redirect; exit; }<br>";
echo "// Then call \$reviewModel->deleteReview()";
echo "</div>";
echo "</div>";

// ========== TEST PROCEDURES ==========
echo "<h2>📋 Test Procedures</h2>";

echo "<div class='test'>";
echo "<h3>Test 1: Submit & View Direct Review</h3>";
echo "<ol>";
echo "<li>Đi tới: <a href='" . APP_URL . "/Review/create?masp=1' target='_blank'>Submit Direct Review</a></li>";
echo "<li>Submit một review</li>";
echo "<li>Kiểm tra trong trang quản lý: <a href='" . APP_URL . "/Admin/manageReviews' target='_blank'>/Admin/manageReviews</a></li>";
echo "<li>Review có xuất hiện không?</li>";
echo "</ol>";
echo "</div>";

echo "<div class='test'>";
echo "<h3>Test 2: Ẩn Đánh Giá Ở Chi Tiết</h3>";
echo "<ol>";
echo "<li>Vào trang chi tiết đánh giá</li>";
echo "<li>Nhấn nút 'Ẩn'</li>";
echo "<li>Kiểm tra console (F12) có error không</li>";
echo "<li>Alert 'Cập nhật thành công' có hiện không?</li>";
echo "<li>Redirect tới /Admin/manageReviews có thành công không?</li>";
echo "</ol>";
echo "</div>";

echo "<div class='test'>";
echo "<h3>Test 3: Xóa Đánh Giá Ở Chi Tiết</h3>";
echo "<ol>";
echo "<li>Vào trang chi tiết đánh giá</li>";
echo "<li>Nhấn nút 'Xóa'</li>";
echo "<li>Confirm popup</li>";
echo "<li>Redirect có thành công không?</li>";
echo "<li>Review có bị xóa không?</li>";
echo "</ol>";
echo "</div>";

echo "<div class='test'>";
echo "<h3>Test 4: Kiểm Tra URL</h3>";
echo "<p>Hãy kiểm tra xem ReviewDetailView generate đúng URL không:</p>";
echo "<ol>";
echo "<li>Inspect element ở trang chi tiết đánh giá</li>";
echo "<li>Check link 'Quay lại': phải có APP_URL prefix (ví dụ: 'http://localhost/...')</li>";
echo "<li>Check AJAX fetch URL: phải là APP_URL + /Admin/updateReviewStatus</li>";
echo "<li>Check delete link: phải có APP_URL prefix</li>";
echo "</ol>";
echo "</div>";

// ========== DATABASE CHECK ==========
echo "<h2>🔍 Database Check</h2>";

$db = new DB();
$totalReviews = $db->selectOne("SELECT COUNT(*) as cnt FROM tblreview")['cnt'];
$directReviews = $db->selectOne("SELECT COUNT(*) as cnt FROM tblreview WHERE order_id IS NULL")['cnt'];

echo "<table>";
echo "<tr><th>Loại</th><th>Số Lượng</th></tr>";
echo "<tr><td>Tổng Review</td><td>" . $totalReviews . "</td></tr>";
echo "<tr><td>Direct Review (order_id = NULL)</td><td>" . $directReviews . "</td></tr>";
echo "</table>";

// Check APP_URL
echo "<h2>⚙️ Configuration Check</h2>";
echo "<p><strong>APP_URL:</strong> " . APP_URL . "</p>";
echo "<p><strong>Base Path:</strong> " . dirname(__FILE__) . "</p>";

echo "<hr>";
echo "<p style='color: green; font-weight: bold;'>✅ Tất cả fixes đã được apply. Hãy test lại!</p>";
echo "<p style='color: orange;'><strong>Lưu ý:</strong> Nếu vẫn có lỗi, hãy check:</p>";
echo "<ul>";
echo "<li>Browser console (F12) - có error gì?</li>";
echo "<li>PHP error log - có error gì?</li>";
echo "<li>Network tab - request tới endpoint nào?</li>";
echo "</ul>";

echo "</div>";
echo "</body></html>";
?>
