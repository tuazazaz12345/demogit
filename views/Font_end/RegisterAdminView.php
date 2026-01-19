<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                    <h4 class="mb-0"><i class="bi bi-person-gear me-2"></i>Đăng ký tài khoản Quản trị</h4>
                </div>

                <div class="card-body p-4">
                    <form action="<?php echo APP_URL; ?>/AuthController/AdminRegister" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên đăng nhập</label>
                            <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" placeholder="Nhập họ và tên..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Nhập email..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-person-plus"></i> Đăng ký
                            </button>
                            <a href="<?php echo APP_URL; ?>/AuthController/ShowAdminLogin" class="text-decoration-none fw-semibold">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập ngay
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light text-center rounded-bottom py-3">
                    <small class="text-muted">© <?php echo date("Y"); ?> Hệ thống quản trị cửa hàng</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm Bootstrap Icon (nếu chưa có) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
