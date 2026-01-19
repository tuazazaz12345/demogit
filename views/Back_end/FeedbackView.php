<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Quản lý góp ý</h1>

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

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Danh sách góp ý</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người gửi</th>
                            <th>Email</th>
                            <th>Nội dung</th>
                            <th>Ngày gửi</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($data['feedbacks']) && !empty($data['feedbacks'])): ?>
                            <?php foreach($data['feedbacks'] as $feedback): ?>
                                <tr>
                                    <td><?= htmlspecialchars($feedback['id']) ?></td>
                                    <td><?= htmlspecialchars($feedback['fullname']) ?></td>
                                    <td><?= htmlspecialchars($feedback['user_email']) ?></td>
                                    <td><?= htmlspecialchars($feedback['content']) ?></td>
                                    <td><?= htmlspecialchars($feedback['created_at']) ?></td>
                                    <td>
                                        <span class="badge <?= $feedback['status'] == 'completed' ? 'bg-success' : 'bg-warning' ?>">
                                            <?= htmlspecialchars($feedback['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($feedback['status'] != 'completed'): ?>
                                            <form action="<?= APP_URL ?>/FeedbackController/updateStatus" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $feedback['id'] ?>">
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Xác nhận hoàn thành góp ý này?')">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form action="<?= APP_URL ?>/FeedbackController/delete" method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?= $feedback['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa góp ý này?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Chưa có góp ý nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.table td {
    vertical-align: middle;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
}
</style>