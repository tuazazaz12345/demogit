<?php
// Test admin manageReviews with both INNER JOIN and LEFT JOIN
require_once 'app/Config.php';

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ADMIN REVIEWS QUERY TEST ===<br><br>";
    
    // Test 1: INNER JOIN (old way - might exclude reviews with missing products)
    echo "<h3>1. INNER JOIN (OLD)</h3>";
    $sql1 = "SELECT r.*, p.tensp FROM tblreview r 
             JOIN tblsanpham p ON r.masp = p.masp 
             ORDER BY r.trangthai DESC, r.ngaygui DESC";
    
    try {
        $stmt = $conn->prepare($sql1);
        $stmt->execute([]);
        $reviews1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Results: " . count($reviews1) . " reviews<br>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        $reviews1 = [];
    }
    
    // Test 2: LEFT JOIN (new way - includes all reviews)
    echo "<br><h3>2. LEFT JOIN (NEW)</h3>";
    $sql2 = "SELECT r.*, p.tensp FROM tblreview r 
             LEFT JOIN tblsanpham p ON r.masp = p.masp 
             ORDER BY r.trangthai DESC, r.ngaygui DESC";
    
    try {
        $stmt = $conn->prepare($sql2);
        $stmt->execute([]);
        $reviews2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Results: " . count($reviews2) . " reviews<br>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        $reviews2 = [];
    }
    
    // Compare
    echo "<br><h3>Comparison</h3>";
    echo "INNER JOIN found: " . count($reviews1) . " reviews<br>";
    echo "LEFT JOIN found: " . count($reviews2) . " reviews<br>";
    
    if (count($reviews2) > count($reviews1)) {
        echo "<span style='color:red'><strong>⚠ " . (count($reviews2) - count($reviews1)) . " reviews are MISSING from INNER JOIN!</strong></span><br>";
        echo "These reviews have products that don't exist in tblsanpham<br><br>";
        
        // Find which reviews are missing
        $ids1 = array_column($reviews1, 'id');
        $ids2 = array_column($reviews2, 'id');
        $missing = array_diff($ids2, $ids1);
        
        if (!empty($missing)) {
            echo "Missing review IDs: " . implode(', ', $missing) . "<br><br>";
            echo "Details of missing reviews:<br>";
            echo "<table border='1' style='border-collapse:collapse'>";
            echo "<tr style='background:#ffe6e6'><th>ID</th><th>masp</th><th>Email</th><th>Rating</th><th>Status</th><th>Product Name</th></tr>";
            foreach ($reviews2 as $r) {
                if (in_array($r['id'], $missing)) {
                    echo "<tr>";
                    echo "<td>" . $r['id'] . "</td>";
                    echo "<td>" . $r['masp'] . "</td>";
                    echo "<td>" . $r['email'] . "</td>";
                    echo "<td>" . $r['sosao'] . "</td>";
                    echo "<td>" . $r['trangthai'] . "</td>";
                    echo "<td>" . ($r['tensp'] ?? '<span style="color:red">NOT FOUND</span>') . "</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        }
    } else {
        echo "<span style='color:green'><strong>✓ All reviews are showing correctly</strong></span>";
    }
    
} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage();
}
?>
