<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                    unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])) : ?>
    <div class="alert alert-success"><?php echo $_SESSION['success'];
                                    unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết khách hàng</h1>
        <a href="<?php echo APP_URL; ?>/Admin/customers" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if (isset($data['customer']) && is_array($data['customer'])): ?>
        <div class="row">
            <!-- Thông tin cơ bản -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin cá nhân</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="font-weight-bold">ID:</span>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($data['customer']['user_id']); ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="font-weight-bold">Họ tên:</span>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($data['customer']['fullname']); ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="font-weight-bold">Email:</span>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($data['customer']['email']); ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="font-weight-bold">Số điện thoại:</span>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($data['customer']['phone'] ?? 'Chưa cập nhật'); ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="font-weight-bold">Địa chỉ:</span>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($data['customer']['address'] ?? 'Chưa cập nhật'); ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="font-weight-bold">Ngày tạo:</span>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($data['customer']['created_at']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thống kê mua sắm</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="card-title">Số lượng đơn hàng</div>
                                        <h3 class="card-text">
                                            <?php 
                                            $stats = isset($data['stats']) ? $data['stats'] : ['total_orders' => 0, 'total_spent' => 0];
                                            echo $stats['total_orders'] ?? 0;
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="card-title">Tổng chi tiêu</div>
                                        <h3 class="card-text">
                                            <?php 
                                            $totalSpent = isset($data['stats']) && isset($data['stats']['total_spent']) 
                                                ? $data['stats']['total_spent'] 
                                                : 0;
                                            echo number_format($totalSpent, 0, ',', '.') . ' đ';
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <strong>Thông tin:</strong> Khách hàng này đã thực hiện 
                            <strong><?php echo $stats['total_orders'] ?? 0; ?></strong> đơn hàng 
                            với tổng giá trị 
                            <strong><?php echo number_format($totalSpent, 0, ',', '.') . ' đ'; ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Hành động -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Hành động</h6>
                    </div>
                    <div class="card-body">
                        <?php 
                        $isLocked = isset($data['customer']['is_locked']) && $data['customer']['is_locked'] == 1;
                        ?>
                        
                        <?php if ($isLocked): ?>
                            <button type="button" class="btn btn-success w-100 mb-2" 
                                    onclick="toggleLock(<?php echo $data['customer']['user_id']; ?>)">
                                <i class="bi bi-unlock"></i> Mở khóa tài khoản
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-warning w-100 mb-2" 
                                    onclick="toggleLock(<?php echo $data['customer']['user_id']; ?>)">
                                <i class="bi bi-lock"></i> Khóa tài khoản
                            </button>
                        <?php endif; ?>
                        
                        <button type="button" class="btn btn-danger w-100" 
                                onclick="deleteCustomer(<?php echo $data['customer']['user_id']; ?>)">
                            <i class="bi bi-trash"></i> Xóa khách hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            Không tìm thấy thông tin khách hàng
        </div>
    <?php endif; ?>
</div>

<script>
function deleteCustomer(id) {
    if (confirm('Bạn có chắc chắn muốn xóa khách hàng này? Thao tác này không thể hoàn tác!')) {
        window.location.href = '<?php echo APP_URL; ?>/Admin/deleteCustomer/' + id;
    }
}

function toggleLock(id) {
    var action = <?php echo $isLocked ? "'mở khóa'" : "'khóa'"; ?>;
    if (confirm('Bạn có chắc chắn muốn ' + action + ' tài khoản này?')) {
        window.location.href = '<?php echo APP_URL; ?>/Admin/toggleLockCustomer/' + id;
    }
}
</script>
