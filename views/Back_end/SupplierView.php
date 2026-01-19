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
        <h1 class="h3 mb-0 text-gray-800">Quản lý nhà cung cấp</h1>
        <a href="<?php echo APP_URL; ?>/SupplierController/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp
        </a>
    </div>

    <!-- Form Tìm kiếm -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo APP_URL; ?>/SupplierController/index" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="keyword" 
                           placeholder="Tìm theo tên, địa chỉ, số điện thoại..." 
                           value="<?php echo htmlspecialchars($data['keyword'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                </div>
                <?php if (!empty($data['keyword'])): ?>
                    <div class="col-md-3">
                        <a href="<?php echo APP_URL; ?>/SupplierController/index" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-clockwise"></i> Xóa bộ lọc
                        </a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Danh sách nhà cung cấp -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Danh sách nhà cung cấp <?php if (!empty($data['keyword'])): ?>(Kết quả tìm kiếm)<?php endif; ?>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%">STT</th>
                            <th>Tên nhà cung cấp</th>
                            <th>Địa chỉ</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Hạn hợp đồng</th>
                            <th>Trạng thái</th>
                            <th style="width: 15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($data['suppliers']) && is_array($data['suppliers']) && count($data['suppliers']) > 0): ?>
                            <?php foreach ($data['suppliers'] as $index => $supplier): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($supplier['ten_ncc']); ?></td>
                                    <td><?php echo htmlspecialchars($supplier['dia_chi'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($supplier['dien_thoai'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($supplier['email'] ?? '-'); ?></td>
                                    <td>
                                        <?php 
                                        if ($supplier['han_hop_dong']) {
                                            $date = new DateTime($supplier['han_hop_dong']);
                                            echo $date->format('d/m/Y');
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($supplier['trang_thai'] == 1): ?>
                                            <span class="badge bg-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Tắt</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo APP_URL; ?>/SupplierController/edit/<?php echo $supplier['id']; ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="deleteSupplier(<?php echo $supplier['id']; ?>)">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <?php if (!empty($data['keyword'])): ?>
                                        Không tìm thấy nhà cung cấp nào phù hợp
                                    <?php else: ?>
                                        Không có nhà cung cấp nào
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
function deleteSupplier(id) {
    if (confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?')) {
        window.location.href = '<?php echo APP_URL; ?>/SupplierController/delete/' + id;
    }
}
</script>
