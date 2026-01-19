<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Test Fix: Invoice tensp Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; }
        .success { background: #d4edda; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #28a745; }
        .fix { background: #d1ecf1; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #0c5460; }
        .code { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>

<div class="container">
    <h1>✅ Fix: Invoice tensp Undefined Key Error</h1>
    <hr>

    <h2>❌ Lỗi Gốc</h2>
    <div class="code">
Warning: Undefined array key "tensp" 
in D:\xamcc\htdocs\phpnangcao\MVC\views\Back_end\InvoiceTemplate.php on line 356
    </div>

    <h2>🔍 Nguyên Nhân</h2>
    <p>Hàm `OrderModel::getOrderDetails()` chỉ select từ bảng `order_details` mà không JOIN với `tblsanpham` để lấy `tensp`.</p>

    <p><strong>Tên cột khác nhau:</strong></p>
    <ul>
        <li><code>order_details.product_name</code> - Tên sản phẩm được lưu khi đặt hàng</li>
        <li><code>tblsanpham.tensp</code> - Tên sản phẩm từ bảng sản phẩm</li>
    </ul>

    <h2>✅ Giải Pháp</h2>

    <div class="fix">
        <h3>Fix 1: Update OrderModel::getOrderDetails()</h3>
        <p>Thêm LEFT JOIN để lấy tensp từ tblsanpham:</p>
        <div class="code">
SELECT od.*, p.tensp 
FROM order_details od
LEFT JOIN tblsanpham p ON od.product_id = p.masp
WHERE od.order_id = ?
        </div>
        <p><strong>Lợi ích:</strong> Nếu sản phẩm bị xóa, vẫn có product_name để fallback</p>
    </div>

    <div class="fix">
        <h3>Fix 2: Update InvoiceTemplate.php</h3>
        <p>Sử dụng COALESCE để handle null values:</p>
        <div class="code">
&lt;?php echo htmlspecialchars(
    $item['tensp'] ?: 
    $item['product_name'] ?: 
    'Sản phẩm không xác định'
); ?&gt;
        </div>
        <p><strong>Lợi ích:</strong> Ưu tiên tensp (tên hiện tại), fallback sang product_name (tên lúc đặt hàng), rồi mới là text lỗi</p>
    </div>

    <h2>📝 File Đã Sửa</h2>

    <div class="success">
        <h3>1. models/OrderModel.php</h3>
        <p>Hàm: <code>getOrderDetails($orderId)</code></p>
        <p>Đổi: SELECT * → SELECT od.*, p.tensp + LEFT JOIN tblsanpham</p>
    </div>

    <div class="success">
        <h3>2. views/Back_end/InvoiceTemplate.php</h3>
        <p>Dòng 356: Thêm fallback logic cho tensp</p>
        <p>Từ: <code>$item['tensp']</code></p>
        <p>Thành: <code>$item['tensp'] ?: $item['product_name'] ?: 'Sản phẩm không xác định'</code></p>
    </div>

    <h2>🧪 Cách Test</h2>

    <ol>
        <li>Vào Quản Trị → Quản Lý Đơn Hàng</li>
        <li>Nhấn nút "In" ở một đơn hàng bất kỳ</li>
        <li>Trang hóa đơn mở</li>
        <li>Kiểm tra:
            <ul>
                <li>✅ Tên sản phẩm hiển thị đầy đủ</li>
                <li>✅ Không có warning error</li>
                <li>✅ Có thể in hóa đơn bình thường</li>
            </ul>
        </li>
    </ol>

    <h2>💡 Ghi Chú</h2>

    <p><strong>Sử dụng LEFT JOIN thay vì INNER JOIN:</strong></p>
    <ul>
        <li>LEFT JOIN - sẽ hiển thị order_details ngay cả khi sản phẩm đã bị xóa</li>
        <li>INNER JOIN - sẽ không hiển thị order_details nếu sản phẩm xóa</li>
    </ul>

    <p><strong>Tại sao cần 3 fallback?</strong></p>
    <ul>
        <li>Scenario 1: Sản phẩm vẫn tồn tại → dùng tensp (tên hiện tại)</li>
        <li>Scenario 2: Sản phẩm bị xóa → dùng product_name (tên lúc đặt)</li>
        <li>Scenario 3: Cả hai null (edge case) → dùng "Sản phẩm không xác định"</li>
    </ul>

    <hr>
    <p style="color: green; font-weight: bold;">✅ Lỗi đã được fix. Hóa đơn sẽ hiển thị tên sản phẩm bình thường.</p>

</div>

</body>
</html>
