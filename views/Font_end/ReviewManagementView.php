<?php
/**
 * ReviewManagementView.php - Quản lý đánh giá & bình luận (Admin)
 */
?>
<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-comments"></i> Quản lý Đánh giá & Bình luận
    </h2>

    <!-- Thống kê -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Tổng cộng</h5>
                    <h3><?php echo $stats['total'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h5 class="card-title">Chờ duyệt</h5>
                    <h3 class="text-warning"><?php echo $stats['pending'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h5 class="card-title">Đã duyệt</h5>
                    <h3 class="text-success"><?php echo $stats['approved'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h5 class="card-title">Ẩn</h5>
                    <h3 class="text-danger"><?php echo $stats['hidden'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="mb-3">
        <a href="?status=all" class="btn <?php echo $currentStatus === 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">
            Tất cả
        </a>
        <a href="?status=pending" class="btn <?php echo $currentStatus === 'pending' ? 'btn-warning' : 'btn-outline-warning'; ?>">
            Chờ duyệt
        </a>
        <a href="?status=approved" class="btn <?php echo $currentStatus === 'approved' ? 'btn-success' : 'btn-outline-success'; ?>">
            Đã duyệt
        </a>
        <a href="?status=hidden" class="btn <?php echo $currentStatus === 'hidden' ? 'btn-danger' : 'btn-outline-danger'; ?>">
            Ẩn
        </a>
    </div>

    <!-- Bảng đánh giá -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th width="50">#</th>
                    <th>Sản phẩm</th>
                    <th>Khách hàng</th>
                    <th>Nội dung</th>
                    <th width="80">Sao</th>
                    <th>Ngày gửi</th>
                    <th width="100">Trạng thái</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($reviews)) {
                    $no = 1;
                    foreach ($reviews as $review) {
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
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($review['tensp']); ?></strong>
                    </td>
                    <td>
                        <div><?php echo htmlspecialchars($review['ten']); ?></div>
                        <small class="text-muted"><?php echo htmlspecialchars($review['email']); ?></small>
                    </td>
                    <td>
                        <div style="max-width: 300px; word-wrap: break-word;">
                            <?php echo htmlspecialchars(substr($review['noidung'], 0, 50)) . (strlen($review['noidung']) > 50 ? '...' : ''); ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            <?php for ($i = 0; $i < $review['sosao']; $i++) { ?>
                                <i class="fas fa-star"></i>
                            <?php } ?>
                        </span>
                    </td>
                    <td>
                        <small><?php echo date('d/m/Y H:i', strtotime($review['ngaygui'])); ?></small>
                    </td>
                    <td>
                        <span class="badge bg-<?php echo $statusClass; ?>">
                            <?php echo $statusIcon; ?> <?php echo htmlspecialchars($review['trangthai']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/Admin/reviewDetail/<?php echo $review['id']; ?>" class="btn btn-info" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if ($review['trangthai'] !== 'đã duyệt') { ?>
                                <button type="button" class="btn btn-success approve-btn" data-id="<?php echo $review['id']; ?>" title="Duyệt">
                                    <i class="fas fa-check"></i>
                                </button>
                            <?php } ?>
                            <?php if ($review['trangthai'] !== 'ẩn') { ?>
                                <button type="button" class="btn btn-warning hide-btn" data-id="<?php echo $review['id']; ?>" title="Ẩn">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            <?php } ?>
                            <button type="button" class="btn btn-danger delete-btn" data-id="<?php echo $review['id']; ?>" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php
                    }
                } else {
                ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2">Không có đánh giá nào</p>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Confirm Delete -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn chắc chắn muốn xóa đánh giá này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a id="confirmDeleteLink" href="#" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Approve button
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-id');
            updateReviewStatus(reviewId, 'đã duyệt');
        });
    });

    // Hide button
    document.querySelectorAll('.hide-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-id');
            updateReviewStatus(reviewId, 'ẩn');
        });
    });

    // Delete button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-id');
            const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            document.getElementById('confirmDeleteLink').href = '/Admin/deleteReview/' + reviewId;
            deleteModal.show();
        });
    });

    // Update review status via AJAX
    function updateReviewStatus(id, status) {
        fetch('/Admin/updateReviewStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}&status=${encodeURIComponent(status)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show updated status
                location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            alert('Lỗi: ' + error);
        });
    }
});
</script>
