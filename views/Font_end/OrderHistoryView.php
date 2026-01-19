<div class="container mt-5">
    <?php if(isset($data['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $data['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📋 Lịch sử đơn hàng của bạn</h2>
        <a href="<?php echo APP_URL; ?>/Home/show" class="btn btn-primary">
            <i class="bi bi-house-door"></i> Quay lại trang chủ
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Mã hóa đơn</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Người nhận</th>
                        <th>Địa chỉ giao hàng</th>
                        <th>Số điện thoại</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
        <tbody>
        <?php if (!empty($data['orders'])): ?>
            <?php foreach ($data['orders'] as $order): ?>
            <tr>
                <td class="text-center"><?= htmlspecialchars($order['order_code']) ?></td>
                <td class="text-center"><?= htmlspecialchars($order['created_at']) ?></td>
                <td class="text-end"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</td>
                <td><?= htmlspecialchars($order['receiver']) ?></td>
                <td><?= htmlspecialchars($order['address']) ?></td>
                <td class="text-center"><?= htmlspecialchars($order['phone']) ?></td>

                <!-- ✅ Sửa đường dẫn này -->
                <td class="text-center">
                    <a href="<?php echo APP_URL; ?>/OrderController/detail/<?= $order['id'] ?>" 
                       class="btn btn-info btn-sm">
                       <i class="bi bi-eye"></i> Xem chi tiết
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center p-5">
                    <div class="text-muted">
                        <i class="bi bi-inbox h3"></i>
                        <p class="mb-0 mt-2">Bạn chưa có đơn hàng nào.</p>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
    </div>
</div>
