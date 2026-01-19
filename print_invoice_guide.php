<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ Thống In Hóa Đơn - Hướng Dẫn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; }
        .feature { background: #d4edda; padding: 20px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #28a745; }
        .feature h3 { color: #155724; margin-bottom: 10px; }
        .code { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; font-family: monospace; }
        table { background: white; margin: 20px 0; }
        thead { background: #007bff; color: white; }
        td { padding: 12px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>

<div class="container">
    <h1>✅ Chức Năng In Hóa Đơn - Quản Trị Đơn Hàng</h1>
    <hr>

    <h2>📋 Tổng Quan</h2>
    <p>Hệ thống quản lý đơn hàng hiện có chức năng in hóa đơn chuyên nghiệp cho mỗi đơn hàng.</p>

    <div class="feature">
        <h3>✨ Tính Năng Chính</h3>
        <ul>
            <li>✅ In hóa đơn đẹp, chuyên nghiệp</li>
            <li>✅ Hiển thị thông tin khách hàng & sản phẩm</li>
            <li>✅ Tính toán tổng tiền tự động</li>
            <li>✅ Hỗ trợ in trực tiếp hoặc lưu PDF</li>
            <li>✅ Responsive design - in từ máy tính hay mobile</li>
            <li>✅ Thiết kế tương thích với tất cả trình duyệt</li>
        </ul>
    </div>

    <h2>🎯 Cách Sử Dụng</h2>

    <h3>Bước 1: Vào Danh Sách Đơn Hàng</h3>
    <p>Quản trị → Quản Lý Đơn Hàng</p>

    <h3>Bước 2: Tìm Đơn Hàng Cần In</h3>
    <p>Tìm kiếm hoặc lọc đơn hàng từ bảng danh sách</p>

    <h3>Bước 3: Nhấn Nút "In"</h3>
    <p>Mỗi dòng trong bảng có 2 nút:</p>
    <ul>
        <li><strong>Xem</strong> - Xem chi tiết đơn hàng</li>
        <li><strong>In</strong> - In hóa đơn (nút xanh <i class="bi bi-printer"></i>)</li>
    </ul>

    <h3>Bước 4: In Hóa Đơn</h3>
    <p>Khi trang hóa đơn mở:</p>
    <ul>
        <li>Nhấn nút "In Hóa Đơn" để mở dialog in</li>
        <li>Chọn máy in hoặc "Save as PDF"</li>
        <li>Nhấn "In" để hoàn tất</li>
    </ul>

    <h2>📄 Nội Dung Hóa Đơn</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Phần</th>
                <th>Nội Dung</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Header</strong></td>
                <td>Logo công ty, địa chỉ, SĐT, email, website</td>
            </tr>
            <tr>
                <td><strong>Thông tin khách hàng</strong></td>
                <td>Tên, email, điện thoại</td>
            </tr>
            <tr>
                <td><strong>Địa chỉ giao hàng</strong></td>
                <td>Địa chỉ giao hàng chi tiết</td>
            </tr>
            <tr>
                <td><strong>Trạng thái đơn hàng</strong></td>
                <td>Chờ xét duyệt / Đã thanh toán / Đang giao / Đã hủy</td>
            </tr>
            <tr>
                <td><strong>Danh sách sản phẩm</strong></td>
                <td>Tên, màu, size, số lượng, đơn giá, thành tiền</td>
            </tr>
            <tr>
                <td><strong>Tính toán</strong></td>
                <td>Tổng tiền hàng, giảm giá, phí vận chuyển, tổng cộng</td>
            </tr>
            <tr>
                <td><strong>Phương thức thanh toán</strong></td>
                <td>Loại thanh toán, dự kiến giao hàng</td>
            </tr>
            <tr>
                <td><strong>Ghi chú</strong></td>
                <td>Ghi chú đơn hàng (nếu có)</td>
            </tr>
        </tbody>
    </table>

    <h2>🔧 File Đã Thêm/Sửa</h2>

    <div class="feature">
        <h3>File Sửa Đổi:</h3>
        <div class="code">
Back_end/OrderListView.php
- Thêm nút "In" ở cột Hành động
        </div>
    </div>

    <div class="feature">
        <h3>File Thêm Mới:</h3>
        <div class="code">
controllers/Admin.php
- Thêm hàm: public function printInvoice($id)

views/Back_end/InvoiceTemplate.php
- Template HTML in hóa đơn
        </div>
    </div>

    <h2>🌐 Route</h2>

    <div class="code">
GET /Admin/printInvoice/{id}
- In hóa đơn cho đơn hàng có ID = {id}
- Mở trang HTML có thể in được
    </div>

    <h2>🎨 Tùy Chỉnh Hóa Đơn</h2>

    <p>Để thay đổi thông tin công ty trên hóa đơn, chỉnh sửa file:</p>
    <div class="code">
views/Back_end/InvoiceTemplate.php - Dòng 70-80
    </div>

    <p>Thay đổi:</p>
    <ul>
        <li>Tên công ty: "🏪 Shop Online"</li>
        <li>Địa chỉ: "123 Đường ABC, TP.HCM"</li>
        <li>SĐT: "(028) 1234 5678"</li>
        <li>Email: "contact@shopOnline.vn"</li>
        <li>Website: "www.shopOnline.vn"</li>
    </ul>

    <h2>✅ Kiểm Tra Chức Năng</h2>

    <div class="feature">
        <h3>Test Steps:</h3>
        <ol>
            <li>Vào Quản Trị → Quản Lý Đơn Hàng</li>
            <li>Tìm một đơn hàng bất kỳ</li>
            <li>Nhấn nút "In" (nút xanh)</li>
            <li>Trang hóa đơn sẽ mở ở tab mới</li>
            <li>Nhấn "In Hóa Đơn"</li>
            <li>Chọn máy in hoặc "Save as PDF"</li>
            <li>Kiểm tra hóa đơn có đầy đủ thông tin không</li>
        </ol>
    </div>

    <h2>💡 Mẹo In</h2>

    <div class="feature">
        <h3>Tiết Kiệm Giấy & Mực</h3>
        <ul>
            <li>Khi in, chọn "A4" hoặc "Letter" cho kích thước giấy</li>
            <li>Chọn "In 2 mặt" để tiết kiệm giấy</li>
            <li>Tắt "Hình nền" nếu chỉ cần in văn bản</li>
        </ul>
    </div>

    <div class="feature">
        <h3>Lưu PDF</h3>
        <ul>
            <li>Chọn máy in: "Save as PDF"</li>
            <li>Chọn folder lưu trữ</li>
            <li>Tên file sẽ là tên trang (có thể đổi tên)</li>
        </ul>
    </div>

    <hr>
    <p style="text-align: center; color: green; font-weight: bold;">✅ Chức năng in hóa đơn đã sẵn sàng sử dụng!</p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
