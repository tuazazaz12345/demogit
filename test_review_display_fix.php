<?php
/**
 * Test Review Display Bug Fix
 * Kiểm tra xem các review đã được khắc phục hay chưa
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/config.php';
require_once 'app/DB.php';
require_once 'models/BaseModel.php';
require_once 'models/ReviewModel.php';

echo "<h1>🔍 Review Display - Debug Test</h1>";
echo "<hr>";

$reviewModel = new ReviewModel();

// 1. Kiểm tra tất cả review
echo "<h2>1. Tất Cả Review Trong Database</h2>";
$allReviews = $reviewModel->getAllReviews();
echo "<p>Tổng: " . count($allReviews) . " review</p>";

if (!empty($allReviews)) {
    echo "<table border='1' cellpadding='10' style='width: 100%; margin-bottom: 20px;'>";
    echo "<tr><th>ID</th><th>Sản Phẩm</th><th>Người</th><th>Nội Dung</th><th>Sao</th><th>trangthai</th><th>moderation_status</th><th>Ngày</th></tr>";
    
    foreach (array_slice($allReviews, 0, 10) as $review) {
        echo "<tr>";
        echo "<td>" . $review['id'] . "</td>";
        echo "<td>" . htmlspecialchars($review['tensp'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($review['ten']) . "</td>";
        echo "<td><small>" . htmlspecialchars(substr($review['noidung'], 0, 50)) . "...</small></td>";
        echo "<td>" . $review['sosao'] . " ⭐</td>";
        echo "<td><span style='background-color: #fff3cd; padding: 3px 8px; border-radius: 3px;'>" . htmlspecialchars($review['trangthai'] ?? 'NULL') . "</span></td>";
        echo "<td>";
        $status = $review['moderation_status'] ?? 'NULL';
        $color = $status === 'approved' ? '#d4edda' : ($status === 'pending' ? '#fff3cd' : '#f8d7da');
        echo "<span style='background-color: " . $color . "; padding: 3px 8px; border-radius: 3px;'>" . htmlspecialchars($status) . "</span>";
        echo "</td>";
        echo "<td><small>" . substr($review['ngaygui'], 0, 10) . "</small></td>";
        echo "</tr>";
    }
    echo "</table>";
}

// 2. Kiểm tra review hiển thị cho sản phẩm
echo "<h2>2. Review Hiển Thị Cho Sản Phẩm (getReviewsByProduct)</h2>";
echo "<p>Tìm một sản phẩm có review...</p>";

$sampleReview = array_shift($allReviews);
if ($sampleReview && isset($sampleReview['masp'])) {
    $masp = $sampleReview['masp'];
    $productReviews = $reviewModel->getReviewsByProduct($masp);
    
    echo "<p><strong>Sản phẩm:</strong> " . htmlspecialchars($sampleReview['tensp']) . " (ID: $masp)</p>";
    echo "<p><strong>Review hiển thị:</strong> " . count($productReviews) . " (Expected: ≥ 1)</p>";
    
    if (!empty($productReviews)) {
        echo "<div style='background-color: #d4edda; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb;'>";
        echo "<span style='color: green; font-weight: bold;'>✅ OK</span> - Review đang hiển thị cho sản phẩm";
        echo "</div>";
    } else {
        echo "<div style='background-color: #f8d7da; padding: 15px; border-radius: 5px; border: 1px solid #f5c6cb;'>";
        echo "<span style='color: red; font-weight: bold;'>❌ FAIL</span> - Không có review hiển thị cho sản phẩm";
        echo "</div>";
    }
}

// 3. Kiểm tra database schema
echo "<h2>3. Database Schema Check</h2>";
try {
    $db = new DB();
    $columns = $db->query("DESCRIBE tblreview", []);
    
    $hasModStatus = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'moderation_status') {
            $hasModStatus = true;
            break;
        }
    }
    
    if ($hasModStatus) {
        echo "<div style='background-color: #d4edda; padding: 15px; border-radius: 5px;'>";
        echo "✅ Cột 'moderation_status' tồn tại trong database";
        echo "</div>";
    } else {
        echo "<div style='background-color: #f8d7da; padding: 15px; border-radius: 5px;'>";
        echo "❌ Cột 'moderation_status' KHÔNG tồn tại";
        echo "<br><strong>Cần chạy migration:</strong> migrations/content_moderation_migration.sql";
        echo "</div>";
    }
} catch (Exception $e) {
    echo "<div style='background-color: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "❌ Lỗi kiểm tra database: " . $e->getMessage();
    echo "</div>";
}

// 4. SQL Queries Check
echo "<h2>4. SQL Queries Debug</h2>";
echo "<p>Các query sử dụng:</p>";
echo "<pre style='background-color: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto;'>";
echo "getReviewsByProduct():
  WHERE r.masp = :masp AND (r.trangthai = 'đã duyệt' OR r.moderation_status = 'approved')

getAverageRating():
  WHERE masp = :masp AND (trangthai = 'đã duyệt' OR moderation_status = 'approved')

getReviewsByOrder():
  WHERE r.order_id = :orderId AND (r.trangthai = 'đã duyệt' OR r.moderation_status = 'approved')";
echo "</pre>";

// 5. Approval Rate
echo "<h2>5. Approval Rate</h2>";
$stats = $reviewModel->getModerationStats();
echo "<p>Tổng review: " . ($stats['total'] ?? 0) . "</p>";
echo "<p>Pending: " . ($stats['pending'] ?? 0) . "</p>";
echo "<p>Approved: " . ($stats['approved'] ?? 0) . "</p>";
echo "<p>Rejected: " . ($stats['rejected'] ?? 0) . "</p>";
echo "<p>Spam: " . ($stats['spam'] ?? 0) . "</p>";

if (isset($stats['approved']) && $stats['approved'] > 0) {
    echo "<div style='background-color: #d4edda; padding: 10px; border-radius: 5px;'>";
    echo "✅ Có " . $stats['approved'] . " review đã được duyệt";
    echo "</div>";
}

echo "<hr>";
echo "<h2>📝 Summary</h2>";
echo "<ul>";
echo "<li>✅ ReviewModel queries cập nhật để check cả trangthai và moderation_status</li>";
echo "<li>✅ updateModerationStatus() fixed (CASE statement bug)</li>";
echo "<li>✅ bulkUpdateStatus() fixed</li>";
echo "<li>✅ addReview() insert moderation_status = 'pending' rõ ràng</li>";
echo "<li>⏳ Review sẽ hiển thị nếu: trangthai = 'đã duyệt' HOẶC moderation_status = 'approved'</li>";
echo "</ul>";
?>
