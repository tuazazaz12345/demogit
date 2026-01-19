<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">
                <i class="bi bi-fire"></i> 🔥 Sản Phẩm Đang Khuyến Mại
            </h2>
            <hr>
        </div>
    </div>

    <?php if (empty($data['promotionalProducts'])): ?>
        <div class="alert alert-info text-center mt-5">
            <h5>📭 Hiện không có sản phẩm nào đang khuyến mại</h5>
            <p class="mb-0">Hãy quay lại sau để xem các khuyến mại mới!</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($data['promotionalProducts'] as $item): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm hover-effect">
                        <!-- Hình ảnh sản phẩm -->
                        <div class="position-relative">
                            <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($item['product']['hinhanh']) ?>" 
                                 class="card-img-top" 
                                 style="height: 250px; object-fit: cover; cursor: pointer;" 
                                 onclick="window.location.href='<?= APP_URL ?>/Home/detail/<?= $item['product']['masp'] ?>'"
                                 alt="<?= htmlspecialchars($item['product']['tensp']) ?>">
                            
                            <!-- Badge khuyến mại -->
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-danger" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="bi bi-percent"></i> -<?= $item['discountPercent'] ?>%
                                </span>
                            </div>
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="card-body">
                            <!-- Tên sản phẩm -->
                            <h6 class="card-title text-truncate" title="<?= htmlspecialchars($item['product']['tensp']) ?>">
                                <?= htmlspecialchars(substr($item['product']['tensp'], 0, 50)) ?>
                            </h6>

                            <!-- Giá -->
                            <div class="mb-2">
                                <p class="text-muted mb-1">
                                    <small>
                                        <del><?= number_format($item['originalPrice'], 0, ',', '.') ?> ₫</del>
                                    </small>
                                </p>
                                <p class="text-danger fw-bold" style="font-size: 18px;">
                                    <?= number_format($item['promotionalPrice'], 0, ',', '.') ?> ₫
                                </p>
                            </div>

                            <!-- Tên khuyến mại -->
                            <p class="mb-3">
                                <small class="text-info">
                                    <i class="bi bi-tag"></i> 
                                    <?= htmlspecialchars(substr($item['promotion']['name'], 0, 40)) ?>
                                </small>
                            </p>

                            <!-- Nút thêm vào giỏ -->
                            <div class="d-grid gap-2">
                                <form method="POST" action="<?= APP_URL ?>/Home/addtocard" class="w-100">
                                    <input type="hidden" name="masp" value="<?= $item['product']['masp'] ?>">
                                    <input type="hidden" name="promotional_price" value="<?= $item['promotionalPrice'] ?>">
                                    <input type="hidden" name="from_promotion" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Footer card -->
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                ✓ Tiết kiệm: <?= number_format($item['originalPrice'] - $item['promotionalPrice'], 0, ',', '.') ?> ₫
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.hover-effect {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-effect:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}

.card-img-top:hover {
    opacity: 0.8;
}
</style>
