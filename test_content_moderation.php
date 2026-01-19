<?php
/**
 * Test Content Moderation System
 * File kiểm tra hệ thống kiểm duyệt nội dung
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load required files
require_once 'app/config.php';
require_once 'app/ContentModerationService.php';

echo "<h1>Content Moderation System - Test</h1>";
echo "<hr>";

// Test cases
$testCases = [
    [
        'name' => 'Normal Review',
        'content' => 'Sản phẩm rất tốt, giao hàng nhanh, đóng gói cẩn thận. Tôi rất hài lòng với chất lượng sách này.',
        'rating' => 5
    ],
    [
        'name' => 'Review with URL (Spam)',
        'content' => 'Tuyệt vời! Mua ở đây http://example.com để có giá rẻ hơn. Rất tốt.',
        'rating' => 5
    ],
    [
        'name' => 'Review with Phone (Suspicious)',
        'content' => 'Sản phẩm tốt nhưng có vấn đề. Liên hệ 0123-456-7890 để hỏi chi tiết thêm.',
        'rating' => 3
    ],
    [
        'name' => 'High Rating + Negative Content',
        'content' => 'Sản phẩm rất tệ, chậm, hư hỏng, lỗi nhiều, không nên mua. Tổn tiền.',
        'rating' => 5
    ],
    [
        'name' => 'Too Much Uppercase',
        'content' => 'ĐÂY LÀ MỘT BÀI ĐÁNH GIÁ VỚI QUÁ NHIỀU CHỮ HOA!!! KHÔNG NÊN VIẾT NHƯ VẬY!!!',
        'rating' => 4
    ],
    [
        'name' => 'Too Short',
        'content' => 'Tốt',
        'rating' => 5
    ],
    [
        'name' => 'Repeat Characters',
        'content' => 'Sản phẩm rất tốooooooooooot!!! Mình yêuuuuuuuu nó!!!!! Cực kỳ hạnh phúc!!!!!',
        'rating' => 5
    ]
];

echo "<h2>Test Cases</h2>";
echo "<table border='1' cellpadding='15' cellspacing='0' style='width: 100%;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>Test Name</th>";
echo "<th>Content</th>";
echo "<th>Rating</th>";
echo "<th>Spam Score</th>";
echo "<th>Status</th>";
echo "<th>Issues</th>";
echo "</tr>";

foreach ($testCases as $test) {
    $result = ContentModerationService::analyzeContent($test['content'], $test['rating']);
    $status = ContentModerationService::getPredictedStatus($result['spam_score']);
    $statusColor = $status === 'spam' ? '#ff6b6b' : ($status === 'approved' ? '#51cf66' : '#ffd93d');
    
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($test['name']) . "</strong></td>";
    echo "<td><small>" . htmlspecialchars(substr($test['content'], 0, 100)) . "...</small></td>";
    echo "<td><center>" . $test['rating'] . " ⭐</center></td>";
    echo "<td><center>";
    echo "<div style='background-color: #e9ecef; border-radius: 5px; padding: 5px;'>";
    echo "<strong>" . $result['spam_score'] . "/100</strong>";
    echo "</div></td>";
    echo "<td><center>";
    echo "<div style='background-color: " . $statusColor . "; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold;'>";
    echo htmlspecialchars(strtoupper($status));
    echo "</div></td>";
    echo "<td>";
    if (!empty($result['issues'])) {
        echo "<ul style='margin: 0; padding-left: 20px;'>";
        foreach ($result['issues'] as $issue) {
            echo "<li><small style='color: red;'>" . htmlspecialchars($issue) . "</small></li>";
        }
        echo "</ul>";
    } else {
        echo "<small style='color: green;'>No issues detected</small>";
    }
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

// Detailed analysis for one sample
echo "<hr>";
echo "<h2>Detailed Analysis Example</h2>";

$sampleContent = 'Sản phẩm tuyệt vời, giao hàng nhanh. Hãy liên hệ 0909-123-456 hoặc email khuyenmai@shop.com để nhận discount!';
$sampleRating = 5;

echo "<h3>Test Content</h3>";
echo "<div style='background-color: #f0f0f0; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
echo "<p><strong>Content:</strong> " . htmlspecialchars($sampleContent) . "</p>";
echo "<p><strong>Rating:</strong> " . $sampleRating . " ⭐</p>";
echo "</div>";

$detailedResult = ContentModerationService::analyzeContent($sampleContent, $sampleRating);

echo "<h3>Analysis Result</h3>";
echo "<table cellpadding='10' style='width: 100%;'>";
echo "<tr><td><strong>Spam Score</strong></td><td>" . $detailedResult['spam_score'] . "/100</td></tr>";
echo "<tr><td><strong>Trust Score</strong></td><td>" . ContentModerationService::getTrustScore($detailedResult['spam_score']) . "/100</td></tr>";
echo "<tr><td><strong>Predicted Status</strong></td><td>";
$predictedStatus = ContentModerationService::getPredictedStatus($detailedResult['spam_score']);
echo "<span style='background-color: " . ($predictedStatus === 'spam' ? '#ff6b6b' : ($predictedStatus === 'approved' ? '#51cf66' : '#ffd93d')) . "; color: white; padding: 5px 10px; border-radius: 3px;'>" . strtoupper($predictedStatus) . "</span>";
echo "</td></tr>";
echo "</table>";

if (!empty($detailedResult['issues'])) {
    echo "<h4>Issues Detected</h4>";
    echo "<ul>";
    foreach ($detailedResult['issues'] as $issue) {
        echo "<li><strong style='color: red;'>" . htmlspecialchars($issue) . "</strong></li>";
    }
    echo "</ul>";
}

if (!empty($detailedResult['warnings'])) {
    echo "<h4>Warnings</h4>";
    echo "<ul>";
    foreach ($detailedResult['warnings'] as $warning) {
        echo "<li><strong style='color: orange;'>" . htmlspecialchars($warning) . "</strong></li>";
    }
    echo "</ul>";
}

if (!empty($detailedResult['prohibited_words_found'])) {
    echo "<h4>Prohibited Words Found</h4>";
    echo "<ul>";
    foreach ($detailedResult['prohibited_words_found'] as $word) {
        echo "<li>" . htmlspecialchars($word) . "</li>";
    }
    echo "</ul>";
}

if (!empty($detailedResult['suspicious_patterns_found'])) {
    echo "<h4>Suspicious Patterns Found</h4>";
    echo "<ul>";
    foreach ($detailedResult['suspicious_patterns_found'] as $pattern) {
        echo "<li><code>" . htmlspecialchars($pattern) . "</code></li>";
    }
    echo "</ul>";
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<div style='background-color: #e7f3ff; padding: 15px; border-radius: 5px;'>";
echo "<p>✅ Hệ thống kiểm duyệt nội dung đang hoạt động bình thường</p>";
echo "<p>🔧 Có thể tùy chỉnh:</p>";
echo "<ul>";
echo "<li>Danh sách từ cấm trong ContentModerationService</li>";
echo "<li>Thresholds spam score</li>";
echo "<li>Thêm các quy tắc kiểm tra mới</li>";
echo "</ul>";
echo "</div>";
?>
