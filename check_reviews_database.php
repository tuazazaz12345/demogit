<?php
/**
 * Check Reviews in Database
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/config.php';
require_once 'app/DB.php';
require_once 'models/BaseModel.php';
require_once 'models/ReviewModel.php';

echo "<h1>🔍 Database Reviews Check</h1>";
echo "<hr>";

$db = new DB();
$reviewModel = new ReviewModel();

// 1. Total reviews
$total = $db->selectOne("SELECT COUNT(*) as cnt FROM tblreview");
echo "<h2>1. Tổng Số Review</h2>";
echo "<p>" . $total['cnt'] . " reviews trong database</p>";

// 2. Reviews by status
echo "<h2>2. Reviews Theo Trạng Thái</h2>";
$statusBreakdown = $db->select(
    "SELECT trangthai, moderation_status, COUNT(*) as cnt FROM tblreview 
     GROUP BY trangthai, moderation_status
     ORDER BY cnt DESC"
);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>trangthai</th><th>moderation_status</th><th>Số lượng</th></tr>";
foreach ($statusBreakdown as $sb) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($sb['trangthai'] ?: 'NULL') . "</td>";
    echo "<td>" . htmlspecialchars($sb['moderation_status'] ?: 'NULL') . "</td>";
    echo "<td>" . $sb['cnt'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// 3. Direct reviews (no order_id)
echo "<h2>3. Direct Reviews (order_id = NULL)</h2>";
$directReviews = $db->select("SELECT id, masp, order_id, trangthai, moderation_status FROM tblreview WHERE order_id IS NULL ORDER BY ngaygui DESC LIMIT 10");
echo "<p>Số lượng: " . count($directReviews) . "</p>";

if (!empty($directReviews)) {
    echo "<table border='1' cellpadding='10' style='font-size: 12px;'>";
    echo "<tr><th>ID</th><th>masp</th><th>trangthai</th><th>moderation_status</th></tr>";
    foreach ($directReviews as $dr) {
        echo "<tr>";
        echo "<td>" . $dr['id'] . "</td>";
        echo "<td>" . $dr['masp'] . "</td>";
        echo "<td>" . htmlspecialchars($dr['trangthai']) . "</td>";
        echo "<td>" . htmlspecialchars($dr['moderation_status']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 4. Check if getPendingReviews includes them
    echo "<h2>4. Check: Có Ở getPendingReviews() Không?</h2>";
    $pending = $reviewModel->getPendingReviews();
    $pendingIds = array_map(fn($p) => $p['id'], $pending);
    
    echo "<p>getPendingReviews() trả: " . count($pending) . " reviews</p>";
    
    foreach ($directReviews as $dr) {
        $found = in_array($dr['id'], $pendingIds);
        $status = $found ? '✅ CÓ' : '❌ KHÔNG';
        echo "<p>Review ID {$dr['id']}: {$status}</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ Không có direct review nào trong database</p>";
    echo "<p>Điều này có thể là vì:</p>";
    echo "<ul>";
    echo "<li>Chưa submit direct review nào</li>";
    echo "<li>Review có order_id (từ đơn hàng)</li>";
    echo "<li>All direct reviews đã bị xóa</li>";
    echo "</ul>";
}

// 5. Show getPendingReviews result
echo "<h2>5. getPendingReviews() Chi Tiết</h2>";
$pending = $reviewModel->getPendingReviews();
echo "<p>Số lượng: " . count($pending) . "</p>";

if (!empty($pending)) {
    echo "<table border='1' cellpadding='10' style='font-size: 12px;'>";
    echo "<tr><th>ID</th><th>order_id</th><th>tensp</th><th>trangthai</th><th>moderation_status</th></tr>";
    foreach (array_slice($pending, 0, 10) as $p) {
        echo "<tr>";
        echo "<td>" . $p['id'] . "</td>";
        echo "<td>" . ($p['order_id'] ?: 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($p['tensp']) . "</td>";
        echo "<td>" . htmlspecialchars($p['trangthai']) . "</td>";
        echo "<td>" . htmlspecialchars($p['moderation_status']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";
echo "<p style='color: green;'><strong>✅ Để submit direct review test:</strong> " . APP_URL . "/test_direct_review_submit.php</p>";
?>
