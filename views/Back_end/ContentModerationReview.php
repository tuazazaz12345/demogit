<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-eye"></i> Kiểm Duyệt Đánh Giá</h2>
        </div>
    </div>
    
    <div class="row">
        <!-- Review Content -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Nội Dung Đánh Giá</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Sản Phẩm:</strong></label>
                        <p><?= htmlspecialchars($data['review']['tensp']) ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Người Dánh Giá:</strong></label>
                        <p>
                            <?= htmlspecialchars($data['review']['ten']) ?> 
                            <br><small class="text-muted"><?= htmlspecialchars($data['review']['email']) ?></small>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Đánh Giá Sao:</strong></label>
                        <div class="rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="bi bi-star-fill <?= $i <= $data['review']['sosao'] ? 'text-warning' : 'text-secondary' ?>" style="font-size: 1.5em;"></i>
                            <?php endfor; ?>
                            <span class="ms-2">(<?= $data['review']['sosao'] ?>/5)</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Nội Dung:</strong></label>
                        <div class="border rounded p-3 bg-light" style="min-height: 150px;">
                            <?= htmlspecialchars($data['review']['noidung']) ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Ngày Gửi:</strong></label>
                            <p><?= date('d/m/Y H:i:s', strtotime($data['review']['ngaygui'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Trạng Thái Hiện Tại:</strong></label>
                            <p>
                                <?php 
                                $status = $data['review']['moderation_status'] ?? $data['review']['trangthai'];
                                $badges = [
                                    'pending' => 'warning',
                                    'chờ duyệt' => 'warning',
                                    'approved' => 'success',
                                    'đã duyệt' => 'success',
                                    'rejected' => 'danger',
                                    'bị từ chối' => 'danger',
                                    'spam' => 'dark'
                                ];
                                $badgeClass = $badges[$status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Analysis & Actions -->
        <div class="col-md-4">
            <!-- Spam Analysis -->
            <div class="card mb-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-robot"></i> Phân Tích Tự Động</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Spam Score:</strong></label>
                        <div class="progress mb-2">
                            <div class="progress-bar <?= $data['analysis']['spam_score'] > 60 ? 'bg-danger' : ($data['analysis']['spam_score'] > 30 ? 'bg-warning' : 'bg-success') ?>" 
                                 style="width: <?= $data['analysis']['spam_score'] ?>%">
                                <?= $data['analysis']['spam_score'] ?>/100
                            </div>
                        </div>
                        <small class="text-muted">
                            <?php 
                            if ($data['analysis']['spam_score'] > 60) {
                                echo 'Mức độ rủi ro: <strong class="text-danger">CAO</strong>';
                            } else if ($data['analysis']['spam_score'] > 30) {
                                echo 'Mức độ rủi ro: <strong class="text-warning">TRUNG BÌNH</strong>';
                            } else {
                                echo 'Mức độ rủi ro: <strong class="text-success">THẤP</strong>';
                            }
                            ?>
                        </small>
                    </div>
                    
                    <?php if (!empty($data['analysis']['issues'])): ?>
                    <div class="mb-3">
                        <label class="form-label"><strong>Vấn Đề Phát Hiện:</strong></label>
                        <ul class="list-unstyled">
                            <?php foreach ($data['analysis']['issues'] as $issue): ?>
                            <li><span class="badge bg-danger"><?= htmlspecialchars($issue) ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['analysis']['warnings'])): ?>
                    <div class="mb-3">
                        <label class="form-label"><strong>Cảnh Báo:</strong></label>
                        <ul class="list-unstyled">
                            <?php foreach ($data['analysis']['warnings'] as $warning): ?>
                            <li><span class="badge bg-warning text-dark"><?= htmlspecialchars($warning) ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Moderation Actions -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-check2-square"></i> Hành Động Duyệt</h5>
                </div>
                <div class="card-body">
                    <!-- Approve -->
                    <form method="POST" action="<?= APP_URL ?>/ContentModerationController/approve" class="mb-3">
                        <input type="hidden" name="review_id" value="<?= $data['review']['id'] ?>">
                        <textarea name="notes" class="form-control form-control-sm mb-2" placeholder="Ghi chú (tùy chọn)"></textarea>
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-check-circle"></i> Duyệt
                        </button>
                    </form>
                    
                    <!-- Reject -->
                    <form method="POST" action="<?= APP_URL ?>/ContentModerationController/reject" class="mb-3">
                        <input type="hidden" name="review_id" value="<?= $data['review']['id'] ?>">
                        <input type="text" name="reason" class="form-control form-control-sm mb-2" placeholder="Lý do từ chối*" required>
                        <textarea name="notes" class="form-control form-control-sm mb-2" placeholder="Ghi chú (tùy chọn)" style="height: 60px;"></textarea>
                        <button type="submit" class="btn btn-warning btn-sm w-100">
                            <i class="bi bi-x-circle"></i> Từ Chối
                        </button>
                    </form>
                    
                    <!-- Mark Spam -->
                    <form method="POST" action="<?= APP_URL ?>/ContentModerationController/markSpam">
                        <input type="hidden" name="review_id" value="<?= $data['review']['id'] ?>">
                        <textarea name="notes" class="form-control form-control-sm mb-2" placeholder="Ghi chú (tùy chọn)"></textarea>
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-exclamation-triangle"></i> Đánh Dấu Spam
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <a href="<?= APP_URL ?>/ContentModerationController/pending" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>
</div>

<style>
.rating i {
    margin-right: 2px;
}
</style>
