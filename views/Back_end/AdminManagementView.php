<?php
if (!isset($_SESSION['admin'])) {
    header("Location: index.php?url=login");
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
}

.admin-card .table {
    margin-bottom: 0;
    font-size: 14px;
}

.admin-card .table thead th {
    font-weight: 600;
    color: #333;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 12px;
}

.admin-card .table tbody td {
    padding: 12px;
    vertical-align: middle;
    border-bottom: 1px solid #e3e6f0;
}

.admin-card .table tbody tr:hover {
    background-color: #f8f9ff;
}

.btn-action {
    padding: 6px 12px !important;
    font-size: 13px;
    margin: 0 2px;
}

.badge-small {
    font-size: 12px;
    padding: 6px 10px !important;
}

h2 {
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}
</style>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-people-fill me-2"></i><?= isset($data['title']) ? htmlspecialchars($data['title']) : 'Quản Lý Quản Trị Viên' ?></h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo $base_url; ?>/Admin/createAdmin" class="btn btn-primary btn-action">
            <i class="bi bi-plus-circle me-2"></i>Tạo Tài Khoản Mới
        </a>
    </div>
</div>

<!-- Alerts -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Danh sách quản trị viên -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover" id="adminsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Đăng Nhập</th>
                    <th>Email</th>
                    <th>Tên Đầy Đủ</th>
                    <th>Ngày Tạo</th>
                    <th class="text-center">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($data['admins']) && is_array($data['admins']) && count($data['admins']) > 0): ?>
                    <?php foreach ($data['admins'] as $admin): ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($admin['id']) ?></strong></td>
                            <td><?= htmlspecialchars($admin['username']) ?></td>
                            <td><?= htmlspecialchars($admin['email']) ?></td>
                            <td><?= htmlspecialchars($admin['fullname']) ?></td>
                            <td><?= isset($admin['created_at']) ? date('d/m/Y H:i', strtotime($admin['created_at'])) : 'N/A' ?></td>
                            <td class="text-center">
                                <a href="<?php echo $base_url; ?>/Admin/editAdmin/<?= $admin['id'] ?>" 
                                   class="btn btn-sm btn-warning btn-action" title="Chỉnh sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($admin['id'] != 1): ?>
                                    <form method="POST" action="<?php echo $base_url; ?>/Admin/deleteAdmin/<?= $admin['id'] ?>" 
                                          class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa tài khoản này?');">
                                        <button type="submit" class="btn btn-sm btn-danger btn-action" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-secondary badge-small">Admin Gốc</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">Chưa có quản trị viên nào</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>