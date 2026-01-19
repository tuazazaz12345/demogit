<!-- Trang Chính Sách Thanh Toán -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold mb-4">Chính Sách Thanh Toán</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-3 mb-4">
            <!-- Sidebar Navigation -->
            <div class="list-group sticky-top" style="top: 20px;">
                <a href="<?= APP_URL ?>/PolicyController/warranty" class="list-group-item list-group-item-action">
                    <i class="bi bi-shield-check"></i> Bảo Hành & Đổi Trả
                </a>
                <a href="<?= APP_URL ?>/PolicyController/payment" class="list-group-item list-group-item-action active">
                    <i class="bi bi-credit-card"></i> Chính Sách Thanh Toán
                </a>
                <a href="<?= APP_URL ?>/PolicyController/shipping" class="list-group-item list-group-item-action">
                    <i class="bi bi-truck"></i> Chính Sách Giao Hàng
                </a>
                <a href="<?= APP_URL ?>/PolicyController/terms" class="list-group-item list-group-item-action">
                    <i class="bi bi-file-text"></i> Điều Khoản Dịch Vụ
                </a>
                <a href="<?= APP_URL ?>/PolicyController/privacy" class="list-group-item list-group-item-action">
                    <i class="bi bi-lock"></i> Chính Sách Bảo Mật
                </a>
            </div>
        </div>
        
        <div class="col-lg-9">
            <!-- Phương Thức Thanh Toán -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-credit-card"></i> Phương Thức Thanh Toán
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">1. Thanh Toán Trực Tuyến</h6>
                    <ul>
                        <li><strong>Cổng VNPay:</strong> Hỗ trợ thanh toán bằng thẻ tín dụng, thẻ ghi nợ, ví điện tử</li>
                        <li><strong>Thẻ Tín Dụng/Ghi Nợ:</strong> Visa, Mastercard, JCB, American Express</li>
                        <li><strong>Ví Điện Tử:</strong> Momo, Zalo Pay, PayPal</li>
                        <li><strong>Chuyển Khoản Ngân Hàng:</strong> Chuyển khoản vào tài khoản cửa hàng</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">2. Thanh Toán Khi Nhận Hàng (COD)</h6>
                    <ul>
                        <li>Khách hàng có thể thanh toán trực tiếp cho shipper khi nhận hàng</li>
                        <li>Không phải lo về an toàn thẻ hoặc thông tin ngân hàng</li>
                        <li>Có thể kiểm tra hàng trước khi thanh toán</li>
                    </ul>
                </div>
            </div>

            <!-- Quy Trình Thanh Toán -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-arrow-left-right"></i> Quy Trình Thanh Toán
                    </h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li><strong>Chọn Phương Thức:</strong> Chọn phương thức thanh toán phù hợp tại giỏ hàng</li>
                        <li><strong>Nhập Thông Tin:</strong> Điền đầy đủ thông tin thanh toán nếu là thanh toán trực tuyến</li>
                        <li><strong>Xác Nhận:</strong> Kiểm tra lại đơn hàng và nhấn xác nhận</li>
                        <li><strong>Hoàn Tất:</strong> Nhận xác nhận đơn hàng qua email</li>
                        <li><strong>Giao Hàng:</strong> Shipper sẽ liên hệ để giao hàng trong thời gian sớm nhất</li>
                    </ol>
                </div>
            </div>

            <!-- Bảo Mật Thanh Toán -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock"></i> Bảo Mật Thanh Toán
                    </h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Tất cả giao dịch được mã hóa SSL (https://)</li>
                        <li>Không lưu trữ thông tin thẻ trên hệ thống của cửa hàng</li>
                        <li>Sử dụng cổng thanh toán uy tín: VNPay, PayPal</li>
                        <li>Tuân thủ các quy định PCI DSS về bảo mật thanh toán</li>
                        <li>Hỗ trợ xác thực 2 lớp (2FA) để bảo vệ tài khoản</li>
                    </ul>
                </div>
            </div>

            <!-- Hoàn Tiền & Hoàn Trừ -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-return"></i> Hoàn Tiền & Hoàn Trừ
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Chính Sách Hoàn Tiền</h6>
                    <ul>
                        <li><strong>Hủy Đơn Hàng:</strong> Hoàn 100% tiền thanh toán nếu hủy trong 24 giờ đầu</li>
                        <li><strong>Sản Phẩm Lỗi:</strong> Hoàn 100% tiền và phí vận chuyển</li>
                        <li><strong>Lỗi Cửa Hàng:</strong> Hoàn 100% tiền nếu giao nhầm sản phẩm</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">Thời Gian Hoàn Tiền</h6>
                    <ul>
                        <li>Thanh toán trực tuyến: 3-5 ngày làm việc</li>
                        <li>Thanh toán COD: Hoàn tiền mặt ngay khi nhận hàng</li>
                        <li>Thanh toán chuyển khoản: 2-3 ngày làm việc</li>
                    </ul>
                </div>
            </div>

            <!-- Ưu Đãi Thanh Toán -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tags"></i> Ưu Đãi Thanh Toán
                    </h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>Giảm Giá:</strong> Sử dụng mã giảm giá để nhận ưu đãi</li>
                        <li><strong>Thanh Toán Ngân Hàng:</strong> Quét mã QR để thanh toán nhanh chóng</li>
                        <li><strong>Hoàn Tiền Momo:</strong> Hoàn 5-10% khi thanh toán bằng Momo</li>
                        <li><strong>Miễn Phí Vận Chuyển:</strong> Cho đơn hàng trên 100,000đ</li>
                        <li><strong>Giá Đặc Biệt:</strong> Thành viên VIP nhận ưu đãi thanh toán riêng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-top: 3px solid #007bff;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    
    .list-group-item {
        border: 1px solid #dee2e6;
    }
    
    .list-group-item.active {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .list-group-item i {
        margin-right: 8px;
    }
    
    .card-header {
        font-weight: 600;
        padding: 1rem;
    }
    
    h6 {
        color: #333;
    }
    
    ul {
        padding-left: 20px;
    }
    
    li {
        margin-bottom: 8px;
        line-height: 1.6;
    }
</style>
