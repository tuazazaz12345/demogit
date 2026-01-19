<?php
$discounts = $data['discounts'] ?? [];
?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2><i class="bi bi-percent"></i> Quản Lý Mã Giảm Giá</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo APP_URL; ?>/DiscountCodeController/create" class="btn btn-primary">
                <i class="bi bi-plus"></i> Thêm Mã Giảm Giá
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Mã</th>
                    <th>Mô Tả</th>
                    <th>Loại Giảm</th>
                    <th>Giá Trị</th>
                    <th>Đơn Hàng Tối Thiểu</th>
                    <th>Lượt Sử Dụng</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($discounts)): ?>
                    <?php foreach ($discounts as $discount): ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($discount['code']); ?></span>
                            </td>
                            <td><?php echo substr(htmlspecialchars($discount['description'] ?? ''), 0, 40); ?>...</td>
                            <td>
                                <?php if ($discount['discount_type'] === 'percentage'): ?>
                                    <span class="badge bg-info">%</span>
                                <?php else: ?>
                                    <span class="badge bg-info">Cố định</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($discount['discount_type'] === 'percentage'): ?>
                                    <?php echo $discount['discount_value']; ?>%
                                <?php else: ?>
                                    <?php echo number_format($discount['discount_value']); ?> ₫
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($discount['min_order_value']); ?> ₫</td>
                            <td>
                                <?php if ($discount['usage_limit']): ?>
                                    <?php echo $discount['used_count']; ?> / <?php echo $discount['usage_limit']; ?>
                                <?php else: ?>
                                    Không giới hạn
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($discount['status']): ?>
                                    <span class="badge bg-success">Kích Hoạt</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tắt</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo APP_URL; ?>/DiscountCodeController/edit/<?php echo $discount['id']; ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <a href="<?php echo APP_URL; ?>/DiscountCodeController/delete/<?php echo $discount['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Không có mã giảm giá nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .bi { margin-right: 5px; }
</style>
