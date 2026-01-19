<?php
// Comprehensive Review Submission Test

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate user session
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'email' => 'testuser@example.com',
        'fullname' => 'Test User'
    ];
    echo "Created test session user<br>";
}

echo "=== COMPREHENSIVE REVIEW SUBMISSION TEST ===<br><br>";

// Step 1: Check database connection
echo "<h3>Step 1: Database Connection</h3>";
try {
    require_once 'app/Config.php';
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Database connected successfully<br>";
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "<br>";
    exit;
}

// Step 2: Check tblreview exists
echo "<h3>Step 2: Table Check</h3>";
try {
    $stmt = $conn->query("SELECT 1 FROM tblreview LIMIT 1");
    echo "✓ tblreview table exists<br>";
} catch (Exception $e) {
    echo "✗ tblreview table not found: " . $e->getMessage() . "<br>";
    exit;
}

// Step 3: Load ReviewModel
echo "<h3>Step 3: Load ReviewModel</h3>";
try {
    require_once 'models/ReviewModel.php';
    $reviewModel = new ReviewModel();
    echo "✓ ReviewModel loaded<br>";
} catch (Exception $e) {
    echo "✗ Error loading ReviewModel: " . $e->getMessage() . "<br>";
    exit;
}

// Step 4: Test addReview method
echo "<h3>Step 4: Test ReviewModel::addReview()</h3>";
$testData = [
    'masp' => 'SP001',
    'ten' => 'Test User',
    'email' => 'test@example.com',
    'noidung' => 'This is a test review with minimum 10 characters',
    'sosao' => 5,
    'order_id' => null
];

try {
    echo "Attempting to insert review...<br>";
    echo "<pre>";
    print_r($testData);
    echo "</pre>";
    
    $reviewId = $reviewModel->addReview($testData);
    
    if ($reviewId) {
        echo "✓ Review inserted successfully! ID: " . $reviewId . "<br>";
        
        // Verify the insert
        echo "<br>Verifying inserted data:<br>";
        $stmt = $conn->prepare("SELECT * FROM tblreview WHERE id = :id");
        $stmt->execute(['id' => $reviewId]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($record) {
            echo "✓ Record found in database:<br>";
            echo "<pre>";
            print_r($record);
            echo "</pre>";
        } else {
            echo "✗ Record not found in database after insert!<br>";
        }
    } else {
        echo "✗ addReview() returned false/null<br>";
    }
} catch (Exception $e) {
    echo "✗ Exception during insert: " . $e->getMessage() . "<br>";
    echo "Trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

// Step 5: List recent reviews
echo "<h3>Step 5: Recent Reviews in Database</h3>";
try {
    $stmt = $conn->query("SELECT * FROM tblreview ORDER BY id DESC LIMIT 5");
    $reviews = $stmt->fetchAll();
    
    echo "Total reviews in last 5: " . count($reviews) . "<br>";
    echo "<table border='1' style='border-collapse:collapse; margin-top:10px'>";
    echo "<tr style='background:#f0f0f0'><th>ID</th><th>masp</th><th>Email</th><th>Rating</th><th>Status</th><th>Date</th></tr>";
    
    foreach ($reviews as $r) {
        echo "<tr>";
        echo "<td>" . $r['id'] . "</td>";
        echo "<td>" . $r['masp'] . "</td>";
        echo "<td>" . $r['email'] . "</td>";
        echo "<td>" . $r['sosao'] . "</td>";
        echo "<td>" . $r['trangthai'] . "</td>";
        echo "<td>" . $r['ngaygui'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "✗ Error fetching reviews: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<a href='javascript:history.back()'>Back</a>";
?>
