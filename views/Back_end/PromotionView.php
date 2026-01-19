<?php
$promotions = $data['promotions'] ?? [];
?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2><i class="bi bi-tag"></i> Quản Lý Khuyến Mại</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo APP_URL; ?>/PromotionController/create" class="btn btn-primary">
                <i class="bi bi-plus"></i> Thêm Khuyến Mại
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
                    <th>ID</th>
                    <th>Tên Khuyến Mại</th>
                    <th>Mô Tả</th>
                    <th>Chiết Khấu (%)</th>
                    <th>Ngày Bắt Đầu</th>
                    <th>Ngày Kết Thúc</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($promotions)): ?>
                    <?php foreach ($promotions as $promo): ?>
                        <tr>
                            <td><?php echo $promo['id']; ?></td>
                            <td><?php echo htmlspecialchars($promo['name']); ?></td>
                            <td><?php echo substr(htmlspecialchars($promo['description'] ?? ''), 0, 50); ?>...</td>
                            <td><span class="badge bg-info"><?php echo $promo['discount_percent']; ?>%</span></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($promo['start_date'])); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($promo['end_date'])); ?></td>
                            <td>
                                <?php if ($promo['status']): ?>
                                    <span class="badge bg-success">Kích Hoạt</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tắt</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo APP_URL; ?>/PromotionController/edit/<?php echo $promo['id']; ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <a href="<?php echo APP_URL; ?>/PromotionController/delete/<?php echo $promo['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Không có khuyến mại nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .bi { margin-right: 5px; }
</style>
