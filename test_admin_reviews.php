<?php
// Debug admin manageReviews query
require_once 'app/Config.php';

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== TESTING ADMIN REVIEW QUERIES ===<br><br>";
    
    // Test 1: All reviews (status filter = all)
    echo "<h3>1. All Reviews (status=all query)</h3>";
    $sql = "SELECT r.*, p.tensp FROM tblreview r 
            JOIN tblsanpham p ON r.masp = p.masp 
            ORDER BY r.trangthai DESC, r.ngaygui DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($reviews) . " reviews<br>";
    if (count($reviews) > 0) {
        echo "<table border='1' style='border-collapse:collapse; width:100%'>";
        echo "<tr style='background:#f0f0f0'><th>ID</th><th>Product</th><th>Email</th><th>Rating</th><th>Status</th><th>Date</th></tr>";
        foreach ($reviews as $r) {
            echo "<tr>";
            echo "<td>" . $r['id'] . "</td>";
            echo "<td>" . $r['tensp'] . "</td>";
            echo "<td>" . $r['email'] . "</td>";
            echo "<td>" . $r['sosao'] . "</td>";
            echo "<td>" . $r['trangthai'] . "</td>";
            echo "<td>" . $r['ngaygui'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test 2: Pending reviews
    echo "<br><h3>2. Pending Reviews (status=pending query)</h3>";
    $sql2 = "SELECT r.*, p.tensp FROM tblreview r 
             JOIN tblsanpham p ON r.masp = p.masp 
             WHERE r.trangthai = 'chờ duyệt' 
             ORDER BY r.ngaygui DESC";
    
    $stmt = $conn->prepare($sql2);
    $stmt->execute([]);
    $pending = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($pending) . " pending reviews<br>";
    if (count($pending) > 0) {
        echo "<table border='1' style='border-collapse:collapse; width:100%'>";
        echo "<tr style='background:#fff3cd'><th>ID</th><th>Product</th><th>Email</th><th>Rating</th><th>Content</th><th>Date</th></tr>";
        foreach ($pending as $r) {
            echo "<tr>";
            echo "<td>" . $r['id'] . "</td>";
            echo "<td>" . $r['tensp'] . "</td>";
            echo "<td>" . $r['email'] . "</td>";
            echo "<td>" . $r['sosao'] . "</td>";
            echo "<td>" . substr($r['noidung'], 0, 50) . "...</td>";
            echo "<td>" . $r['ngaygui'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test 3: Stats
    echo "<br><h3>3. Review Statistics</h3>";
    $sql3 = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN trangthai = 'chờ duyệt' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN trangthai = 'đã duyệt' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN trangthai = 'ẩn' THEN 1 ELSE 0 END) as hidden
            FROM tblreview";
    
    $stmt = $conn->prepare($sql3);
    $stmt->execute([]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Total: " . $stats['total'] . "<br>";
    echo "Pending (chờ duyệt): " . $stats['pending'] . "<br>";
    echo "Approved (đã duyệt): " . $stats['approved'] . "<br>";
    echo "Hidden (ẩn): " . $stats['hidden'] . "<br>";
    
    // Test 4: Check if products exist
    echo "<br><h3>4. Sample Products</h3>";
    $sql4 = "SELECT masp, tensp FROM tblsanpham LIMIT 5";
    $stmt = $conn->prepare($sql4);
    $stmt->execute([]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Sample products:<br>";
    foreach ($products as $p) {
        echo "- " . $p['masp'] . ": " . $p['tensp'] . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
