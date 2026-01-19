<?php
$pendingRequests = $data['pendingRequests'] ?? [];
$approvedRequests = $data['approvedRequests'] ?? [];
$rejectedRequests = $data['rejectedRequests'] ?? [];
$totalPending = $data['totalPending'] ?? 0;
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-3">
                <i class="bi bi-person-check"></i> Quản Lý Duyệt Tài Khoản Admin
            </h2>
            <hr>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- NAV TABS -->
    <ul class="nav nav-tabs" id="approvalTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                <i class="bi bi-hourglass-split"></i> Chờ Duyệt
                <span class="badge bg-warning ms-2"><?php echo count($pendingRequests); ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                <i class="bi bi-check-circle"></i> Đã Phê Duyệt
                <span class="badge bg-success ms-2"><?php echo count($approvedRequests); ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button">
                <i class="bi bi-x-circle"></i> Bị Từ Chối
                <span class="badge bg-danger ms-2"><?php echo count($rejectedRequests); ?></span>
            </button>
        </li>
    </ul>

    <!-- TAB CONTENT -->
    <div class="tab-content" id="approvalTabsContent">

        <!-- ==================== CHỜ DUYỆT ==================== -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="mt-4">
                <?php if (empty($pendingRequests)): ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-check-circle"></i> Không có yêu cầu nào đang chờ duyệt
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-warning">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Đăng Nhập</th>
                                    <th>Email</th>
                                    <th>Họ Tên</th>
                                    <th>Ngày Yêu Cầu</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingRequests as $req): ?>
                                    <tr>
                                        <td><strong>#<?php echo $req['id']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($req['username']); ?></td>
                                        <td><?php echo htmlspecialchars($req['email']); ?></td>
                                        <td><?php echo htmlspecialchars($req['fullname']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($req['requested_at'])); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo APP_URL; ?>/AdminApprovalController/detail/<?php echo $req['id']; ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Chi Tiết
                                                </a>
                                                <form method="POST" action="<?php echo APP_URL; ?>/AdminApprovalController/approve/<?php echo $req['id']; ?>" 
                                                      style="display:inline;">
                                                    <button type="submit" class="btn btn-sm btn-success" 
                                                            onclick="return confirm('Xác nhận phê duyệt?')">
                                                        <i class="bi bi-check"></i> Phê Duyệt
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo $req['id']; ?>">
                                                    <i class="bi bi-x"></i> Từ Chối
                                                </button>
                                            </div>

                                            <!-- Modal từ chối -->
                                            <div class="modal fade" id="rejectModal<?php echo $req['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Từ Chối Yêu Cầu</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="<?php echo APP_URL; ?>/AdminApprovalController/reject/<?php echo $req['id']; ?>">
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Lý do từ chối:</label>
                                                                    <textarea class="form-control" name="reason" rows="3" required 
                                                                              placeholder="Nhập lý do từ chối..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                <button type="submit" class="btn btn-danger">Xác Nhận Từ Chối</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ==================== ĐÃ PHÊ DUYỆT ==================== -->
        <div class="tab-pane fade" id="approved" role="tabpanel">
            <div class="mt-4">
                <?php if (empty($approvedRequests)): ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Không có tài khoản nào đã được phê duyệt
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-success">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Đăng Nhập</th>
                                    <th>Email</th>
                                    <th>Họ Tên</th>
                                    <th>Ngày Yêu Cầu</th>
                                    <th>Ngày Duyệt</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($approvedRequests as $req): ?>
                                    <tr>
                                        <td><strong>#<?php echo $req['id']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($req['username']); ?></td>
                                        <td><?php echo htmlspecialchars($req['email']); ?></td>
                                        <td><?php echo htmlspecialchars($req['fullname']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($req['requested_at'])); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($req['approved_at'])); ?></td>
                                        <td>
                                            <a href="<?php echo APP_URL; ?>/AdminApprovalController/detail/<?php echo $req['id']; ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Chi Tiết
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ==================== BỊ TỪ CHỐI ==================== -->
        <div class="tab-pane fade" id="rejected" role="tabpanel">
            <div class="mt-4">
                <?php if (empty($rejectedRequests)): ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Không có yêu cầu nào bị từ chối
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-danger">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Đăng Nhập</th>
                                    <th>Email</th>
                                    <th>Họ Tên</th>
                                    <th>Ngày Yêu Cầu</th>
                                    <th>Ngày Từ Chối</th>
                                    <th>Lý Do</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rejectedRequests as $req): ?>
                                    <tr>
                                        <td><strong>#<?php echo $req['id']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($req['username']); ?></td>
                                        <td><?php echo htmlspecialchars($req['email']); ?></td>
                                        <td><?php echo htmlspecialchars($req['fullname']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($req['requested_at'])); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($req['approved_at'])); ?></td>
                                        <td><?php echo htmlspecialchars(substr($req['rejection_reason'], 0, 50)); ?></td>
                                        <td>
                                            <a href="<?php echo APP_URL; ?>/AdminApprovalController/detail/<?php echo $req['id']; ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Chi Tiết
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    
    .btn-group {
        display: flex;
        gap: 5px;
    }
</style>
