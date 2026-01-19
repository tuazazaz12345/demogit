<?php
if (!isset($_SESSION['user'])) {
    header('Location: ' . APP_URL . '/AuthController/showLogin');
    exit();
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-person-circle me-2"></i>Thông tin cá nhân</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($data['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $data['success']; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($data['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $data['error']; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo APP_URL; ?>/Home/updateProfile" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" readonly>
                            <small class="text-muted">Email không thể thay đổi</small>
                        </div>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" 
                                value="<?php echo htmlspecialchars($_SESSION['user']['fullname']); ?>" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập họ và tên
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                value="<?php echo htmlspecialchars($data['user']['phone'] ?? ''); ?>" 
                                pattern="[0-9]{10}" title="Số điện thoại phải có 10 chữ số">
                            <div class="invalid-feedback">
                                Vui lòng nhập số điện thoại hợp lệ (10 chữ số)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($data['user']['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Để đổi mật khẩu, vui lòng truy cập trang 
                            <a href="<?php echo APP_URL; ?>/Home/showChangePassword" class="alert-link">Đổi mật khẩu</a>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Lưu thay đổi
                            </button>
                            <a href="<?php echo APP_URL; ?>/Home/show" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại trang chủ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Kiểm tra form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            // Kiểm tra mật khẩu xác nhận
            var password = document.getElementById('new_password')
            var confirm = document.getElementById('confirm_password')
            if (password.value !== confirm.value) {
                confirm.setCustomValidity('Mật khẩu xác nhận không khớp')
                event.preventDefault()
                event.stopPropagation()
            } else {
                confirm.setCustomValidity('')
            }
            
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>