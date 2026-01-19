<!-- Trang Chính Sách Giao Hàng -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold mb-4">Chính Sách Giao Hàng</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-3 mb-4">
            <!-- Sidebar Navigation -->
            <div class="list-group sticky-top" style="top: 20px;">
                <a href="<?= APP_URL ?>/PolicyController/warranty" class="list-group-item list-group-item-action">
                    <i class="bi bi-shield-check"></i> Bảo Hành & Đổi Trả
                </a>
                <a href="<?= APP_URL ?>/PolicyController/payment" class="list-group-item list-group-item-action">
                    <i class="bi bi-credit-card"></i> Chính Sách Thanh Toán
                </a>
                <a href="<?= APP_URL ?>/PolicyController/shipping" class="list-group-item list-group-item-action active">
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
            <!-- Khu Vực Giao Hàng -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-geo-alt"></i> Khu Vực Giao Hàng
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Phạm Vi Giao Hàng</h6>
                    <ul>
                        <li><strong>Toàn Quốc:</strong> Giao hàng đến tất cả các tỉnh thành Việt Nam</li>
                        <li><strong>Thành Phố Hồ Chí Minh:</strong> Giao hàng miễn phí cho đơn từ 50,000đ</li>
                        <li><strong>Các Tỉnh Khác:</strong> Phí giao hàng tính theo khoảng cách</li>
                        <li><strong>Hải Phòng, Hà Nội:</strong> Giao nhanh 24h - 48h</li>
                    </ul>
                </div>
            </div>

            <!-- Thời Gian Giao Hàng -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock"></i> Thời Gian Giao Hàng
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Khu Vực</th>
                                <th>Thời Gian Giao Hàng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>TPHCM (Quận 1-7, 12)</strong></td>
                                <td>24h - 48h</td>
                            </tr>
                            <tr>
                                <td><strong>TPHCM (Quận/Huyện Khác)</strong></td>
                                <td>24h - 72h</td>
                            </tr>
                            <tr>
                                <td><strong>Hà Nội</strong></td>
                                <td>24h - 48h</td>
                            </tr>
                            <tr>
                                <td><strong>Hải Phòng, Đà Nẵng</strong></td>
                                <td>48h - 72h</td>
                            </tr>
                            <tr>
                                <td><strong>Các Tỉnh Khác</strong></td>
                                <td>3-7 ngày</td>
                            </tr>
                            <tr>
                                <td><strong>Các Huyện Miền Núi</strong></td>
                                <td>7-15 ngày</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Phí Giao Hàng -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-coin"></i> Bảng Phí Giao Hàng
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Thành Phố Hồ Chí Minh</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Giá Trị Đơn Hàng</th>
                                <th>Phí Giao Hàng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dưới 50,000đ</td>
                                <td>20,000đ</td>
                            </tr>
                            <tr>
                                <td>50,000đ - 100,000đ</td>
                                <td>Miễn phí</td>
                            </tr>
                            <tr>
                                <td>Trên 100,000đ</td>
                                <td>Miễn phí</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h6 class="fw-bold mb-3 mt-4">Các Tỉnh Khác</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Giá Trị Đơn Hàng</th>
                                <th>Phí Giao Hàng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dưới 100,000đ</td>
                                <td>30,000đ - 50,000đ</td>
                            </tr>
                            <tr>
                                <td>100,000đ - 300,000đ</td>
                                <td>30,000đ</td>
                            </tr>
                            <tr>
                                <td>Trên 300,000đ</td>
                                <td>Miễn phí</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quy Trình Giao Hàng -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-arrow-repeat"></i> Quy Trình Giao Hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">1. Xác Nhận Đơn Hàng</h6>
                                <p>Cửa hàng xác nhận đơn hàng của bạn trong 1-2 giờ</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">2. Chuẩn Bị Hàng</h6>
                                <p>Nhân viên chuẩn bị hàng và kiểm tra chất lượng</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">3. Gửi Shipper</h6>
                                <p>Shipper nhận hàng và lên kế hoạch giao hàng</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">4. Liên Hệ Khách Hàng</h6>
                                <p>Shipper gọi xác nhận địa chỉ giao hàng</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">5. Giao Hàng</h6>
                                <p>Shipper giao hàng đến địa chỉ của bạn</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chính Sách Hư Hỏng -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle"></i> Hàng Hư Hỏng Trong Vận Chuyển
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Hàng Hư Hỏng Do Shipper</h6>
                    <ul>
                        <li>Cửa hàng sẽ liên hệ shipper để bồi thường 100%</li>
                        <li>Khách hàng được chọn: hoàn tiền hoặc giao lại sản phẩm mới</li>
                        <li>Thời gian xử lý: 3-5 ngày làm việc</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">Cách Liên Hệ Để Báo Cáo Hư Hỏng</h6>
                    <ul>
                        <li>Chụp ảnh hàng hư hỏng tại thời điểm nhận</li>
                        <li>Liên hệ Hotline: <strong>0123-456-789</strong></li>
                        <li>Email: <strong>support@bookstore.com</strong></li>
                        <li>Chat trực tiếp qua website</li>
                        <li>Có công chứng viên (nếu giá trị hàng lớn)</li>
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
    
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline-item {
        display: flex;
        margin-bottom: 30px;
        position: relative;
    }
    
    .timeline-marker {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 20px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }
    
    .timeline-content h6 {
        margin-top: 0;
        margin-bottom: 5px;
    }
    
    .timeline-content p {
        margin: 0;
        color: #666;
        font-size: 0.95rem;
    }
</style>
