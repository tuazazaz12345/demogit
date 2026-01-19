<div class="container mt-5 mb-5">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Thông tin chung đơn hàng -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Chi tiết đơn hàng #<?= htmlspecialchars($data['order']['order_code'] ?? 'N/A') ?></h4>
        </div>
        <div class="card-body">
            <?php if (!empty($data['order'])): $order = $data['order']; ?>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <p><strong>Ngày đặt:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
                        <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['receiver']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($order['user_email']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($order['address']) ?></p>
                        <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                        <p>
                            <strong>Trạng thái:</strong>
                            <span class="badge 
                                <?php 
                                    switch($order['trangthai']) {
                                        case 'chờ xét duyệt': echo 'bg-warning text-dark'; break;
                                        case 'đang giao hàng': echo 'bg-info text-dark'; break;
                                        case 'đã thanh toán': echo 'bg-success'; break;
                                        case 'đã hủy': echo 'bg-danger'; break;
                                        default: echo 'bg-secondary';
                                    }
                                ?>">
                                <?= htmlspecialchars($order['trangthai']) ?>
                            </span>
                        </p>
                    </div>
                </div>
                <hr>
                <h5 class="text-end">
                    <strong>Tổng tiền:</strong>
                    <span class="text-danger fs-5"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</span>
                </h5>
            <?php else: ?>
                <p class="text-muted text-center">Không tìm thấy thông tin đơn hàng.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Danh sách sản phẩm trong đơn -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Sản phẩm trong đơn hàng</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($data['details'])): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th>Mã SP</th>
                                <th>Tên sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Số lượng</th>
                                <th>Giá bán</th>
                                <th>Giá khuyến mãi</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data['details'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_id']) ?></td>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td>
                                    <img src="<?php echo APP_URL; ?>/public/images/<?= htmlspecialchars($item['image'] ?? 'defaut.png') ?>" 
                                         class="img-thumbnail" style="width: 60px; height: 60px; object-fit: contain;">
                                </td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td><?= number_format($item['price'], 0, ',', '.') ?> ₫</td>
                                <td><?= number_format($item['sale_price'], 0, ',', '.') ?> ₫</td>
                                <td class="text-danger"><strong><?= number_format($item['total'], 0, ',', '.') ?> ₫</strong></td>
                                <td>
                                    <?php 
                                    $hasReviewed = false;
                                    if (isset($data['reviews']) && is_array($data['reviews'])) {
                                        foreach ($data['reviews'] as $r) {
                                            if ($r['masp'] == $item['product_id']) {
                                                $hasReviewed = true; ?>
                                                <div class="review-content">
                                                    <div class="rating">
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <i class="bi bi-star-fill <?= $i <= $r['sosao'] ? 'text-warning' : 'text-secondary' ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <p class="mb-0"><?= htmlspecialchars($r['noidung']) ?></p>
                                                </div>
                                                <?php
                                                break;
                                            }
                                        }
                                    }
                                    if (!$hasReviewed && $data['order']['trangthai'] == 'đã thanh toán'): ?>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal<?= $item['product_id'] ?>">
                                            Đánh giá
                                        </button>
                                        <!-- Modal Đánh giá -->
                                        <div class="modal fade" id="reviewModal<?= $item['product_id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Đánh giá sản phẩm</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="<?= APP_URL ?>/ReviewController/add" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="masp" value="<?= $item['product_id'] ?>">
                                                            <input type="hidden" name="order_id" value="<?= $data['order']['id'] ?>">
                                                            <div class="mb-3">
                                                                <label>Đánh giá</label>
                                                                <div class="rating-input">
                                                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                                                        <input type="radio" name="sosao" value="<?= $i ?>" id="star<?= $i ?><?= $item['product_id'] ?>" required>
                                                                        <label for="star<?= $i ?><?= $item['product_id'] ?>"><i class="bi bi-star-fill"></i></label>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="noidung" class="form-label">Nhận xét của bạn</label>
                                                                <textarea class="form-control" name="noidung" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <style>
                    .rating-input {
                        display: flex;
                        flex-direction: row-reverse;
                        justify-content: flex-end;
                    }
                    .rating-input input {
                        display: none;
                    }
                    .rating-input label {
                        cursor: pointer;
                        font-size: 1.5em;
                        color: #ddd;
                        margin: 0 2px;
                    }
                    .rating-input input:checked ~ label,
                    .rating-input label:hover,
                    .rating-input label:hover ~ label {
                        color: #ffc107;
                    }
                </style>
            <?php else: ?>
                <p class="text-center text-muted">Không có sản phẩm nào trong đơn hàng này.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Nút quay lại -->
    <div class="text-center mt-4">
        <a href="http://localhost/phpnangcao/MVC/Home/orderHistory" class="btn btn-secondary px-4">
            ← Quay lại lịch sử đơn hàng
        </a>
    </div>
</div>
