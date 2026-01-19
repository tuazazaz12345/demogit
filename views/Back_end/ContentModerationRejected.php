<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-x-circle"></i> Đánh Giá Spam / Bị Từ Chối</h2>
            <p class="text-muted">Tổng: <?= count($data['reviews']) ?> đánh giá</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= APP_URL ?>/ContentModerationController" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại Dashboard
            </a>
        </div>
    </div>
    
    <?php if (empty($data['reviews'])): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Không có đánh giá nào bị từ chối hoặc spam
    </div>
    <?php else: ?>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Sản Phẩm</th>
                    <th>Người Dánh Giá</th>
                    <th>Nội Dung</th>
                    <th>Sao</th>
                    <th>Trạng Thái</th>
                    <th>Lý Do / Ghi Chú</th>
                    <th>Ngày Xử Lý</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['reviews'] as $review): ?>
                <tr>
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
                        <?php 
                        $status = $review['moderation_status'] ?? 'unknown';
                        $badges = [
                            'rejected' => 'danger',
                            'spam' => 'dark'
                        ];
                        $badgeClass = $badges[$status] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                    </td>
                    <td>
                        <small>
                            <?php 
                            if ($review['ly_do_tu_choi']) {
                                echo '<strong>Lý do:</strong> ' . htmlspecialchars($review['ly_do_tu_choi']) . '<br>';
                            }
                            if ($review['moderation_notes']) {
                                echo '<strong>Ghi chú:</strong> ' . htmlspecialchars($review['moderation_notes']);
                            }
                            if (!$review['ly_do_tu_choi'] && !$review['moderation_notes']) {
                                echo 'Không có ghi chú';
                            }
                            ?>
                        </small>
                    </td>
                    <td>
                        <small><?= isset($review['moderation_date']) && $review['moderation_date'] ? date('d/m/Y H:i', strtotime($review['moderation_date'])) : 'N/A' ?></small>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php endif; ?>
</div>
