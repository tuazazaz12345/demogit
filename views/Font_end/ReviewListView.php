<?php
/**
 * ReviewListView.php - Danh sách đánh giá sản phẩm (khách hàng)
 */
?>
<div class="container mt-4">
    <h4 class="mb-4">
        <i class="fas fa-comments"></i> Đánh Giá & Bình Luận 
        <?php if (!empty($reviews)): ?>
            <span class="badge bg-primary"><?php echo count($reviews); ?></span>
        <?php endif; ?>
    </h4>

    <!-- Thống kê đánh giá -->
    <?php if (!empty($reviews)): ?>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center border-0 bg-light">
                    <div class="card-body">
                        <h5 class="card-title text-warning">
                            <?php for ($i = 0; $i < 5; $i++) { ?>
                                <i class="fas fa-star"></i>
                            <?php } ?>
                        </h5>
                        <p class="mb-0">
                            <strong><?php echo number_format($average, 1); ?>/5</strong>
                        </p>
                        <small class="text-muted"><?php echo count($reviews); ?> đánh giá</small>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Danh sách đánh giá -->
    <?php if (!empty($reviews)): ?>
        <div class="reviews-list">
            <?php foreach ($reviews as $review): ?>
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0">
                                    <strong><?php echo htmlspecialchars($review['ten']); ?></strong>
                                </h6>
                                <small class="text-muted"><?php echo htmlspecialchars($review['email']); ?></small>
                            </div>
                            <small class="text-muted">
                                <?php echo date('d/m/Y', strtotime($review['ngaygui'])); ?>
                            </small>
                        </div>

                        <!-- Đánh giá sao -->
                        <div class="mb-2">
                            <span class="text-warning">
                                <?php for ($i = 0; $i < 5; $i++) { ?>
                                    <?php if ($i < $review['sosao']): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php } ?>
                            </span>
                            <span class="ms-2">
                                <strong><?php echo $review['sosao']; ?>/5</strong>
                            </span>
                        </div>

                        <!-- Nội dung -->
                        <p class="mb-0">
                            <?php echo nl2br(htmlspecialchars($review['noidung'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Chưa có đánh giá -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Chưa có đánh giá nào. Hãy mua sản phẩm và đánh giá sau khi thanh toán.
        </div>
    <?php endif; ?>
</div>

<style>
.reviews-list {
    max-height: 600px;
    overflow-y: auto;
}

.reviews-list::-webkit-scrollbar {
    width: 6px;
}

.reviews-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.reviews-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.reviews-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
