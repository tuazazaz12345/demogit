<?php
if (!isset($_SESSION['admin'])) {
    header("Location: " . (defined('APP_URL') ? APP_URL : '/phpnangcao/MVC') . "/AuthController/ShowAdminLogin");
    exit;
}

$base_url = defined('APP_URL') ? APP_URL : '/phpnangcao/MVC';
?>

<style>
.admin-card {
    border-radius: 8px;
    border: 1px solid #e3e6f0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    background-color: #fff;
    padding: 30px;
}

.admin-card .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.admin-card .form-control,
.admin-card .form-select {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 10px 12px;
    font-size: 14px;
}

.admin-card .form-control:focus,
.admin-card .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.admin-card .btn {
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 6px;
    font-weight: 500;
}

.admin-card .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.admin-card .btn-primary:hover {
    background-color: #0056b3;
}

.admin-card .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.admin-card .btn-secondary:hover {
    background-color: #545b62;
}

h2 {
    font-weight: 600;
    color: #333;
    margin-bottom: 25px;
}

.alert {
    border-radius: 6px;
    margin-bottom: 20px;
}
</style>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><?= isset($data['title']) ? htmlspecialchars($data['title']) : 'Quản Lý Quản Trị Viên' ?></h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo $base_url; ?>/Admin/manageAdmins" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay Lại
        </a>
    </div>
</div>

<!-- Alerts -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="admin-card">
    <form method="POST" action="<?php 
        if (isset($data['action']) && $data['action'] == 'edit') {
            echo $base_url . '/Admin/updateAdmin/' . $data['admin']['id'];
        } else {
            echo $base_url . '/Admin/storeAdmin';
        }
    ?>">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Tên Đăng Nhập 
                    <?php if (!isset($data['action']) || $data['action'] == 'create'): ?>
                        <span class="text-danger">*</span>
                    <?php endif; ?>
                </label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="<?php if (isset($data['admin'])) echo htmlspecialchars($data['admin']['username']); ?>"
                       <?php if (!isset($data['action']) || $data['action'] == 'create') echo 'required'; ?> 
                       <?php if (isset($data['action']) && $data['action'] == 'edit') echo 'readonly'; ?>>
                <small class="text-muted">Không thể thay đổi sau khi tạo</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?php if (isset($data['admin'])) echo htmlspecialchars($data['admin']['email']); ?>"
                       required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="fullname" class="form-label">Tên Đầy Đủ</label>
                <input type="text" class="form-control" id="fullname" name="fullname"
                       value="<?php if (isset($data['admin'])) echo htmlspecialchars($data['admin']['fullname']); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Mật Khẩu 
                    <?php if (!isset($data['action']) || $data['action'] == 'create'): ?>
                        <span class="text-danger">*</span>
                    <?php else: ?>
                        <small class="text-muted">(Để trống nếu không thay đổi)</small>
                    <?php endif; ?>
                </label>
                <input type="password" class="form-control" id="password" name="password"
                       <?php if (!isset($data['action']) || $data['action'] == 'create') echo 'required'; ?>>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-2"></i>
                <?php 
                    if (isset($data['action']) && $data['action'] == 'edit') {
                        echo 'Cập Nhật';
                    } else {
                        echo 'Tạo Mới';
                    }
                ?>
            </button>
            <a href="<?php echo $base_url; ?>/Admin/manageAdmins" class="btn btn-secondary">
                <i class="bi bi-x-circle me-2"></i>Hủy
            </a>
        </div>
    </form>
</div>