<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-hourglass-split"></i> Đánh Giá Chờ Duyệt</h2>
            <p class="text-muted">Tổng: <?= count($data['reviews']) ?> đánh giá</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= APP_URL ?>/ContentModerationController" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại Dashboard
            </a>
        </div>
    </div>
    
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (empty($data['reviews'])): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Không có đánh giá nào chờ duyệt
    </div>
    <?php else: ?>
    
    <form method="POST" action="<?= APP_URL ?>/ContentModerationController/bulkApprove" id="bulkForm">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>Sản Phẩm</th>
                        <th>Người Dánh Giá</th>
                        <th>Nội Dung</th>
                        <th>Sao</th>
                        <th>Ngày Gửi</th>
                        <th>Spam Score</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['reviews'] as $review): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="review_ids[]" value="<?= $review['id'] ?>" class="review-checkbox">
                        </td>
                        <td>
                            <small><?= htmlspecialchars($review['tensp']) ?></small>
                        </td>
                        <td>
                            <small><?= htmlspecialchars($review['ten']) ?></small><br>
                            <span class="text-muted" style="font-size: 0.85em;"><?= htmlspecialchars($review['email']) ?></span>
                        </td>
                        <td>
                            <small><?= htmlspecialchars(substr($review['noidung'], 0, 100)) ?>...</small>
                        </td>
                        <td>
                            <div class="rating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star-fill <?= $i <= $review['sosao'] ? 'text-warning' : 'text-secondary' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td>
                            <small><?= date('d/m/Y H:i', strtotime($review['ngaygui'])) ?></small>
                        </td>
                        <td>
                            <?php 
                            $spamScore = $review['flagged_as_spam'] ?? 0;
                            $badgeClass = $spamScore > 60 ? 'danger' : ($spamScore > 30 ? 'warning' : 'success');
                            ?>
                            <span class="badge bg-<?= $badgeClass ?>"><?= $spamScore ?>/100</span>
                        </td>
                        <td>
                            <a href="<?= APP_URL ?>/ContentModerationController/review/<?= $review['id'] ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-6">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check"></i> Duyệt Được Chọn
                </button>
            </div>
        </div>
    </form>
    
    <?php endif; ?>
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.review-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>
