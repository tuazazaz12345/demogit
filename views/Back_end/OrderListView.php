<div class="container mt-4">
    <h2 class="text-center mb-4 fw-bold text-primary">📦 Danh sách đơn hàng</h2>

    <!-- Hiển thị thông báo -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Bộ lọc tìm kiếm & lọc trạng thái -->
    <form class="row mb-4 g-3 align-items-center" method="get" action="<?php echo APP_URL; ?>/Admin/listOrders">
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" 
                       name="keyword" 
                       class="form-control"
                       placeholder="🔍 Tìm theo ID hoặc mã đơn hàng..."
                       value="<?= htmlspecialchars($data['keyword'] ?? '') ?>">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= ($data['status'] ?? '') === 'all' ? 'selected' : '' ?>>📋 Tất cả trạng thái</option>
                <option value="chờ xét duyệt" <?= ($data['status'] ?? '') === 'chờ xét duyệt' ? 'selected' : '' ?>>🕓 Chờ xét duyệt</option>
                <option value="đang giao hàng" <?= ($data['status'] ?? '') === 'đang giao hàng' ? 'selected' : '' ?>>🚚 Đang giao hàng</option>
                <option value="đã thanh toán" <?= ($data['status'] ?? '') === 'đã thanh toán' ? 'selected' : '' ?>>✅ Đã thanh toán</option>
                <option value="đã hủy" <?= ($data['status'] ?? '') === 'đã hủy' ? 'selected' : '' ?>>❌ Đã hủy</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-outline-primary w-100 shadow-sm" type="submit">Lọc</button>
        </div>
    </form>

    <!-- Bảng danh sách đơn hàng -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Mã đơn</th>
                    <th>Người nhận</th>
                    <th>Email</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['orders'])): ?>
                    <?php foreach ($data['orders'] as $order): ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary"><?= $order['id'] ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($order['order_code']) ?></td>
                            <td><?= htmlspecialchars($order['receiver'] ?: '—') ?></td>
                            <td><?= htmlspecialchars($order['user_email']) ?></td>
                            <td class="text-end text-danger fw-bold"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</td>
                            <td class="text-center">
                                <span class="badge px-3 py-2 
                                    <?php 
                                        switch($order['trangthai']) {
                                            case 'chờ xét duyệt': echo 'bg-warning text-dark'; break;
                                            case 'đang giao hàng': echo 'bg-info text-dark'; break;
                                            case 'đã thanh toán': echo 'bg-success'; break;
                                            case 'đã hủy': echo 'bg-danger'; break;
                                            default: echo 'bg-secondary';
                                        }
                                    ?>">
                                    <?= htmlspecialchars(ucfirst($order['trangthai'])) ?>
                                </span>
                            </td>
                            <td class="text-center text-muted"><?= htmlspecialchars($order['created_at']) ?></td>
                            <td class="text-center">
                                <a href="<?php echo APP_URL; ?>/Admin/orderDetail/<?= $order['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary me-2">
                                    Xem
                                </a>
                                <a href="<?php echo APP_URL; ?>/Admin/printInvoice/<?= $order['id'] ?>" 
                                   class="btn btn-sm btn-outline-success" target="_blank" title="In hóa đơn">
                                    <i class="bi bi-printer"></i> In
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">
                            Không tìm thấy đơn hàng nào phù hợp.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
