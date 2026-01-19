<!-- Trang Chính Sách Bảo Hành & Đổi Trả -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold mb-4">Chính Sách Bảo Hành & Đổi Trả</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-3 mb-4">
            <!-- Sidebar Navigation -->
            <div class="list-group sticky-top" style="top: 20px;">
                <a href="<?= APP_URL ?>/PolicyController/warranty" class="list-group-item list-group-item-action active">
                    <i class="bi bi-shield-check"></i> Bảo Hành & Đổi Trả
                </a>
                <a href="<?= APP_URL ?>/PolicyController/payment" class="list-group-item list-group-item-action">
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
            <!-- Bảo Hành -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check"></i> Chính Sách Bảo Hành
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">1. Phạm Vị Bảo Hành</h6>
                    <ul>
                        <li>Bảo hành 100% tiền hàng nếu sản phẩm bị lỗi trong 7 ngày đầu</li>
                        <li>Sách phải còn nguyên vẹn, không có dấu hiệu sử dụng</li>
                        <li>Phải còn hóa đơn và tem bảo hành nguyên vẹn</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">2. Điều Kiện Không Bảo Hành</h6>
                    <ul>
                        <li>Sách bị rách, gập góc, chồn ố</li>
                        <li>Khách hàng tự ý sửa chữa hoặc tác động vật lý</li>
                        <li>Sản phẩm không còn tem bảo hành hoặc hóa đơn</li>
                        <li>Quá hạn bảo hành (7 ngày)</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">3. Quy Trình Bảo Hành</h6>
                    <ol>
                        <li>Liên hệ chúng tôi qua chat hoặc hotline với ảnh chứng minh lỗi</li>
                        <li>Gửi sản phẩm về cửa hàng (chúng tôi trả cước)</li>
                        <li>Chúng tôi kiểm tra trong 2-3 ngày làm việc</li>
                        <li>Thay mới hoặc hoàn tiền (tùy chọn của khách)</li>
                    </ol>
                </div>
            </div>
            
            <!-- Đổi Trả -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-arrow-left-right"></i> Chính Sách Đổi Trả
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">1. Điều Kiện Đổi Trả</h6>
                    <ul>
                        <li>Đổi trả miễn phí trong 30 ngày nếu không hài lòng</li>
                        <li>Sản phẩm phải nguyên vẹn, chưa sử dụng</li>
                        <li>Còn tem bảo hành và hóa đơn gốc</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">2. Sản Phẩm Không Được Đổi Trả</h6>
                    <ul>
                        <li>Sách đã được sử dụng hoặc có dấu hiệu đọc</li>
                        <li>Sản phẩm bị ẩm mốc, nhuộn nước</li>
                        <li>Quá thời hạn đổi trả (30 ngày)</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">3. Quy Trình Đổi Trả</h6>
                    <ol>
                        <li>Khách hàng liên hệ để xin phép đổi trả</li>
                        <li>Gửi sản phẩm về cửa hàng (khách trả cước)</li>
                        <li>Chúng tôi kiểm tra trong 2 ngày làm việc</li>
                        <li>Gửi sản phẩm mới về hoặc hoàn tiền</li>
                    </ol>
                    
                    <div class="alert alert-info mt-4">
                        <strong>Lưu Ý:</strong> Khách hàng có thể chọn giữ sản phẩm cũ khi yêu cầu hoàn tiền 
                        (sẽ được giảm 5% giá trị từ hoàn tiền).
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item {
        border: none;
        border-bottom: 1px solid #e9ecef;
    }
    
    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .card {
        border: 0;
    }
    
    .card-header {
        border: 0;
        padding: 1.25rem;
    }
</style>
