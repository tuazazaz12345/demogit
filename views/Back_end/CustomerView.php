<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                    unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])) : ?>
    <div class="alert alert-success"><?php echo $_SESSION['success'];
                                    unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý khách hàng</h1>
    </div>
    
    <!-- Form Tìm kiếm -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo APP_URL; ?>/Admin/customers" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="keyword" 
                           placeholder="Tìm theo tên hoặc email..." 
                           value="<?php echo htmlspecialchars($data['keyword'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                </div>
                <?php if (!empty($data['keyword'])): ?>
                    <div class="col-md-3">
                        <a href="<?php echo APP_URL; ?>/Admin/customers" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-clockwise"></i> Xóa bộ lọc
                        </a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <!-- Danh sách khách hàng -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Danh sách khách hàng <?php if (!empty($data['keyword'])): ?>(Kết quả tìm kiếm)<?php endif; ?>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Họ tên</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($data['customers']) && is_array($data['customers']) && count($data['customers']) > 0) : ?>
                            <?php foreach ($data['customers'] as $customer) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($customer['user_id']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['phone'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($customer['address'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($customer['created_at']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" 
                                                onclick="viewCustomerDetails(<?php echo $customer['user_id']; ?>)">
                                            <i class="bi bi-eye"></i> Xem
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="deleteCustomer(<?php echo $customer['user_id']; ?>)">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <?php if (!empty($data['keyword'])): ?>
                                        Không tìm thấy khách hàng nào phù hợp
                                    <?php else: ?>
                                        Không có khách hàng nào
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCustomer(id) {
    if (confirm('Bạn có chắc chắn muốn xóa khách hàng này?')) {
        window.location.href = '<?php echo APP_URL; ?>/Admin/deleteCustomer/' + id;
    }
}

function viewCustomerDetails(id) {
    window.location.href = '<?php echo APP_URL; ?>/Admin/customerDetails/' + id;
}
</script>