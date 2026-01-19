<!-- Trang Chính Sách Bảo Mật -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold mb-4">Chính Sách Bảo Mật</h1>
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
                <a href="<?= APP_URL ?>/PolicyController/shipping" class="list-group-item list-group-item-action">
                    <i class="bi bi-truck"></i> Chính Sách Giao Hàng
                </a>
                <a href="<?= APP_URL ?>/PolicyController/terms" class="list-group-item list-group-item-action">
                    <i class="bi bi-file-text"></i> Điều Khoản Dịch Vụ
                </a>
                <a href="<?= APP_URL ?>/PolicyController/privacy" class="list-group-item list-group-item-action active">
                    <i class="bi bi-lock"></i> Chính Sách Bảo Mật
                </a>
            </div>
        </div>
        
        <div class="col-lg-9">
            <!-- Giới Thiệu -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock"></i> Giới Thiệu
                    </h5>
                </div>
                <div class="card-body">
                    <p>Chúng tôi cam kết bảo vệ dữ liệu cá nhân của bạn. Chính sách bảo mật này giải thích cách chúng tôi thu thập, sử dụng, chia sẻ và bảo vệ thông tin của bạn khi bạn sử dụng website của chúng tôi.</p>
                </div>
            </div>

            <!-- Dữ Liệu Thu Thập -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-database"></i> Thông Tin Chúng Tôi Thu Thập
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Thông Tin Bạn Cung Cấp</h6>
                    <ul>
                        <li>Tên đầy đủ, email, số điện thoại</li>
                        <li>Địa chỉ giao hàng và địa chỉ thanh toán</li>
                        <li>Thông tin thẻ tín dụng (xử lý bởi VNPay, không lưu trên server)</li>
                        <li>Câu hỏi bảo mật, câu trả lời xác thực</li>
                        <li>Bình luận, đánh giá sản phẩm</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">Thông Tin Được Thu Thập Tự Động</h6>
                    <ul>
                        <li>Địa chỉ IP của bạn</li>
                        <li>Loại trình duyệt và hệ điều hành</li>
                        <li>Trang web bạn truy cập trước đó</li>
                        <li>Thời gian và thời lượng lưu lại trang</li>
                        <li>Cookies và pixel tracking</li>
                        <li>Thông tin vị trí (với sự cho phép của bạn)</li>
                    </ul>
                </div>
            </div>

            <!-- Cách Sử Dụng Dữ Liệu -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-gear"></i> Chúng Tôi Sử Dụng Dữ Liệu Như Thế Nào
                    </h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>Xử lý đơn hàng:</strong> Giao hàng, thanh toán, liên hệ khách hàng</li>
                        <li><strong>Cải thiện dịch vụ:</strong> Phân tích hành vi người dùng, A/B testing</li>
                        <li><strong>Bảo mật:</strong> Phát hiện gian lận, bảo vệ chống hack</li>
                        <li><strong>Marketing:</strong> Gửi khuyến mại, email thông báo (bạn có thể hủy đăng ký)</li>
                        <li><strong>Tuân thủ pháp luật:</strong> Lưu hóa đơn cho kế toán</li>
                        <li><strong>Nghiên cứu:</strong> Khảo sát khách hàng, phân tích thị trường</li>
                    </ul>
                </div>
            </div>

            <!-- Chia Sẻ Dữ Liệu -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-share"></i> Chia Sẻ Dữ Liệu Với Bên Thứ Ba
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Chúng Tôi Chia Sẻ Dữ Liệu Với</h6>
                    <ul>
                        <li><strong>Công ty vận chuyển:</strong> Địa chỉ giao hàng, tên, số điện thoại</li>
                        <li><strong>Cổng thanh toán:</strong> VNPay, PayPal (chỉ thông tin cần thiết)</li>
                        <li><strong>Nhà cung cấp dịch vụ:</strong> Email, hosting, analytics</li>
                        <li><strong>Cơ quan chính phủ:</strong> Khi yêu cầu hợp pháp</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">Chúng Tôi KHÔNG Chia Sẻ</h6>
                    <ul>
                        <li>Thông tin thẻ tín dụng (được VNPay xử lý)</li>
                        <li>Mật khẩu tài khoản của bạn</li>
                        <li>Dữ liệu với đối tác quảng cáo mà không có sự đồng ý</li>
                        <li>Thông tin lịch sử mua hàng cho bên thứ ba</li>
                    </ul>
                </div>
            </div>

            <!-- Bảo Vệ Dữ Liệu -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lock-fill"></i> Chúng Tôi Bảo Vệ Dữ Liệu Như Thế Nào
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Biện Pháp Bảo Mật</h6>
                    <ul>
                        <li><strong>Mã hóa SSL/TLS:</strong> Tất cả dữ liệu được mã hóa trong quá trình truyền (https://)</li>
                        <li><strong>Mật khẩu Hash:</strong> Mật khẩu được hash bằng bcrypt, không lưu plaintext</li>
                        <li><strong>Tường lửa:</strong> Server được bảo vệ bằng tường lửa và IDS</li>
                        <li><strong>Kiểm tra SQL Injection:</strong> Sử dụng prepared statements</li>
                        <li><strong>Backup định kỳ:</strong> Dữ liệu được sao lưu hàng ngày</li>
                        <li><strong>Kiểm trao lỗi bảo mật:</strong> Bug bounty program cho lỗi bảo mật</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">Bạn Cũng Cần Làm</h6>
                    <ul>
                        <li>Sử dụng mật khẩu mạnh (tối thiểu 8 ký tự)</li>
                        <li>Không chia sẻ mật khẩu với ai</li>
                        <li>Đăng xuất sau khi sử dụng máy công cộng</li>
                        <li>Báo cáo hoạt động bất thường ngay lập tức</li>
                    </ul>
                </div>
            </div>

            <!-- Quyền của Bạn -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-hand-thumbs-up"></i> Quyền Của Bạn Về Dữ Liệu
                    </h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>Quyền truy cập:</strong> Yêu cầu xem dữ liệu chúng tôi có về bạn</li>
                        <li><strong>Quyền chỉnh sửa:</strong> Cập nhật thông tin cá nhân của bạn bất kỳ lúc nào</li>
                        <li><strong>Quyền xóa:</strong> Yêu cầu xóa tài khoản và dữ liệu của bạn</li>
                        <li><strong>Quyền từ chối:</strong> Hủy đăng ký email marketing</li>
                        <li><strong>Quyền khiếu nại:</strong> Khiếu nại với cơ quan bảo vệ dữ liệu</li>
                    </ul>
                    <p class="mt-3"><strong>Để thực hiện quyền của bạn:</strong> Liên hệ support@bookstore.com</p>
                </div>
            </div>

            <!-- Cookies -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cookie"></i> Cookies
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Chúng Tôi Sử Dụng Cookies Để</h6>
                    <ul>
                        <li>Ghi nhớ tùy chọn của bạn</li>
                        <li>Theo dõi hoạt động của bạn để phân tích</li>
                        <li>Hiển thị quảng cáo liên quan</li>
                        <li>Cải thiện trải nghiệm người dùng</li>
                    </ul>
                    
                    <h6 class="fw-bold mb-3 mt-4">Cách Kiểm Soát Cookies</h6>
                    <ul>
                        <li>Bạn có thể tắt cookies trong cài đặt trình duyệt</li>
                        <li>Tắt cookies có thể ảnh hưởng đến chức năng website</li>
                        <li>Chúng tôi tôn trọng lựa chọn "Do Not Track" của bạn</li>
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
    
    p {
        line-height: 1.7;
    }
</style>
