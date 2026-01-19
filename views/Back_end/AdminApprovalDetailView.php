<?php
$request = $data['request'] ?? null;
$approvedByAdmin = $data['approvedByAdmin'] ?? null;

if (!$request) {
    echo '<div class="alert alert-danger">Yêu cầu không tồn tại</div>';
    return;
}
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>
                <i class="bi bi-clipboard-check"></i> Chi Tiết Yêu Cầu Duyệt Admin
                <span class="badge 
                    <?php 
                        if ($request['status'] === 'pending') echo 'bg-warning';
                        elseif ($request['status'] === 'approved') echo 'bg-success';
                        else echo 'bg-danger';
                    ?>
                ">
                    <?php 
                        if ($request['status'] === 'pending') echo 'Chờ Duyệt';
                        elseif ($request['status'] === 'approved') echo 'Đã Phê Duyệt';
                        else echo 'Bị Từ Chối';
                    ?>
                </span>
            </h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo APP_URL; ?>/AdminApprovalController/index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông Tin Yêu Cầu</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">ID Yêu Cầu:</th>
                            <td><strong>#<?php echo $request['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <th>Tên Đăng Nhập:</th>
                            <td><?php echo htmlspecialchars($request['username']); ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo htmlspecialchars($request['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Họ Tên Đầy Đủ:</th>
                            <td><?php echo htmlspecialchars($request['fullname']); ?></td>
                        </tr>
                        <tr>
                            <th>Ngày Yêu Cầu:</th>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($request['requested_at'])); ?></td>
                        </tr>
                        <tr>
                            <th>Trạng Thái:</th>
                            <td>
                                <span class="badge 
                                    <?php 
                                        if ($request['status'] === 'pending') echo 'bg-warning';
                                        elseif ($request['status'] === 'approved') echo 'bg-success';
                                        else echo 'bg-danger';
                                    ?>
                                ">
                                    <?php 
                                        if ($request['status'] === 'pending') echo 'Chờ Duyệt';
                                        elseif ($request['status'] === 'approved') echo 'Đã Phê Duyệt';
                                        else echo 'Bị Từ Chối';
                                    ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if ($request['status'] !== 'pending'): ?>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Thông Tin Xử Lý</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Ngày Xử Lý:</th>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($request['approved_at'])); ?></td>
                            </tr>
                            <tr>
                                <th>Xử Lý Bởi:</th>
                                <td>
                                    <?php if ($approvedByAdmin): ?>
                                        <strong><?php echo htmlspecialchars($approvedByAdmin['fullname']); ?></strong>
                                        (<?php echo htmlspecialchars($approvedByAdmin['username']); ?>)
                                    <?php else: ?>
                                        <span class="text-muted">Không có dữ liệu</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ($request['status'] === 'rejected'): ?>
                                <tr>
                                    <th>Lý Do Từ Chối:</th>
                                    <td>
                                        <p class="alert alert-danger mb-0">
                                            <?php echo htmlspecialchars($request['rejection_reason']); ?>
                                        </p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Hành Động</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form method="POST" action="<?php echo APP_URL; ?>/AdminApprovalController/approve/<?php echo $request['id']; ?>" 
                                      onsubmit="return confirm('Xác nhận phê duyệt tài khoản này?');">
                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        <i class="bi bi-check-circle"></i> Phê Duyệt
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-danger btn-lg w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="bi bi-x-circle"></i> Từ Chối
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông Tin Bổ Sung</h5>
                </div>
                <div class="card-body">
                    <p><strong>Admin ID:</strong> #<?php echo $request['admin_id']; ?></p>
                    <p><strong>Số lần kiểm tra:</strong> 1</p>
                    <hr>
                    <p class="text-muted"><small>Cập nhật lần cuối: <?php echo date('d/m/Y H:i', strtotime($request['requested_at'])); ?></small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal từ chối -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Từ Chối Yêu Cầu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo APP_URL; ?>/AdminApprovalController/reject/<?php echo $request['id']; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do từ chối: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reason" name="reason" rows="5" required 
                                  placeholder="Nhập chi tiết lý do từ chối..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <small>Lý do này sẽ được gửi đến email của người yêu cầu.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận từ chối yêu cầu này?');">
                        Xác Nhận Từ Chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
    
    .card-header {
        border-bottom: 1px solid #dee2e6;
    }
</style>
