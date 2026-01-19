<?php
/**
 * ReviewDetailView.php - Xem chi tiết đánh giá
 */
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <a href="<?php echo isset($backUrl) ? htmlspecialchars($backUrl) : (APP_URL . '/Admin/manageReviews'); ?>" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-comment"></i> Chi tiết Đánh giá
                    </h5>
                </div>

                <div class="card-body">
                    <!-- Sản phẩm -->
                    <div class="mb-4">
                        <h6 class="text-muted">Sản phẩm:</h6>
                        <a href="/DetailProduct/index/<?php echo $review['masp']; ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                            <?php echo htmlspecialchars($review['tensp']); ?>
                        </a>
                    </div>

                    <!-- Thông tin khách hàng -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Tên khách hàng:</h6>
                            <p><?php echo htmlspecialchars($review['ten']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Email:</h6>
                            <p><a href="mailto:<?php echo htmlspecialchars($review['email']); ?>"><?php echo htmlspecialchars($review['email']); ?></a></p>
                        </div>
                    </div>

                    <!-- Đánh giá sao -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Đánh giá sao:</h6>
                            <div style="font-size: 1.5rem;">
                                <?php for ($i = 0; $i < 5; $i++) { ?>
                                    <i class="fas fa-star <?php echo $i < $review['sosao'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                <?php } ?>
                                <span class="ms-2">(<strong><?php echo $review['sosao']; ?></strong>/5)</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Ngày gửi:</h6>
                            <p><?php echo date('d/m/Y H:i', strtotime($review['ngaygui'])); ?></p>
                        </div>
                    </div>

                    <!-- Nội dung bình luận -->
                    <div class="mb-4">
                        <h6 class="text-muted">Nội dung:</h6>
                        <div class="alert alert-light border">
                            <?php echo nl2br(htmlspecialchars($review['noidung'])); ?>
                        </div>
                    </div>

                    <!-- Trạng thái -->
                    <div class="mb-4">
                        <h6 class="text-muted">Trạng thái:</h6>
                        <?php
                        $statusClass = match($review['trangthai']) {
                            'chờ duyệt' => 'warning',
                            'đã duyệt' => 'success',
                            'ẩn' => 'danger',
                            default => 'secondary'
                        };

                        $statusIcon = match($review['trangthai']) {
                            'chờ duyệt' => '<i class="fas fa-clock"></i>',
                            'đã duyệt' => '<i class="fas fa-check"></i>',
                            'ẩn' => '<i class="fas fa-eye-slash"></i>',
                            default => ''
                        };
                        ?>
                        <span class="badge bg-<?php echo $statusClass; ?>" style="font-size: 1rem;">
                            <?php echo $statusIcon; ?> <?php echo htmlspecialchars($review['trangthai']); ?>
                        </span>
                    </div>

                    <!-- Hành động -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted mb-3">Thao tác:</h6>
                        <div class="btn-group" role="group">
                            <?php if ($review['trangthai'] !== 'đã duyệt') { ?>
                                <button type="button" class="btn btn-success" onclick="updateStatus('đã duyệt')">
                                    <i class="fas fa-check"></i> Duyệt
                                </button>
                            <?php } ?>

                            <?php if ($review['trangthai'] !== 'ẩn') { ?>
                                <button type="button" class="btn btn-warning" onclick="updateStatus('ẩn')">
                                    <i class="fas fa-eye-slash"></i> Ẩn
                                </button>
                            <?php } ?>

                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (!confirm('Bạn chắc chắn muốn cập nhật trạng thái?')) return;

    fetch('<?php echo APP_URL; ?>/Admin/updateReviewStatus', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=<?php echo $review['id']; ?>&status=${encodeURIComponent(status)}`
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response không phải JSON. Status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Cập nhật thành công');
            location.href = '<?php echo isset($backUrl) ? htmlspecialchars($backUrl) : (APP_URL . '/Admin/manageReviews'); ?>';
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        alert('Lỗi: ' + error);
        console.error('Chi tiết lỗi:', error);
    });
}

function confirmDelete() {
    if (!confirm('Bạn chắc chắn muốn xóa đánh giá này?')) return;
    location.href = '<?php echo APP_URL; ?>/Admin/deleteReview/<?php echo $review['id']; ?>';
}
</script>
