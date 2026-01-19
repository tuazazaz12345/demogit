<div class="container-fluid px-4">
    <h1 class="mt-4">Quản Lý Hàng Tồn Kho</h1>
    
    <!-- Search Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= APP_URL ?>/StatisticsController/inventory" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Tìm theo tên sản phẩm..." 
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="code" placeholder="Tìm theo mã sản phẩm..." 
                        value="<?= isset($_GET['code']) ? htmlspecialchars($_GET['code']) : '' ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">-- Trạng thái --</option>
                        <option value="critical" <?= isset($_GET['status']) && $_GET['status'] === 'critical' ? 'selected' : '' ?>>Cần nhập thêm (≤5)</option>
                        <option value="low" <?= isset($_GET['status']) && $_GET['status'] === 'low' ? 'selected' : '' ?>>Sắp hết (≤10)</option>
                        <option value="good" <?= isset($_GET['status']) && $_GET['status'] === 'good' ? 'selected' : '' ?>>Đủ hàng</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                </div>
                <div class="col-md-1">
                    <a href="<?= APP_URL ?>/StatisticsController/inventory" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Danh Sách Hàng Tồn Kho
            <?php if (isset($_GET['search']) || isset($_GET['code']) || isset($_GET['status'])): ?>
                <span class="badge bg-info ms-2">Kết quả tìm kiếm</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="inventoryTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã SP</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Loại SP</th>
                            <th>Tồn Kho</th>
                            <th>Đã Bán</th>
                            <th>Trạng Thái</th>
                            <th>Giá Nhập</th>
                            <th>Giá Bán</th>
                            <th>Tổng Giá Trị</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['products'] as $product): ?>
                            <?php 
                                $stockStatus = '';
                                $statusClass = '';
                                
                                if ($product['soluong'] <= 5) {
                                    $stockStatus = 'Cần nhập thêm';
                                    $statusClass = 'text-danger';
                                } elseif ($product['soluong'] <= 10) {
                                    $stockStatus = 'Sắp hết';
                                    $statusClass = 'text-warning';
                                } else {
                                    $stockStatus = 'Đủ hàng';
                                    $statusClass = 'text-success';
                                }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($product['masp']) ?></td>
                                <td><?= htmlspecialchars($product['tensp']) ?></td>
                                <td><?= htmlspecialchars($product['tenLoaiSP']) ?></td>
                                <td><?= number_format($product['soluong']) ?></td>
                                <td><?= number_format($product['sold_count'] ?? 0) ?></td>
                                <td class="<?= $statusClass ?>"><?= $stockStatus ?></td>
                                <td><?= number_format($product['giaNhap']) ?> ₫</td>
                                <td><?= number_format($product['giaXuat']) ?> ₫</td>
                                <td><?= number_format($product['soluong'] * $product['giaNhap']) ?> ₫</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Thống kê tổng quan -->
            <div class="row mt-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <h4 class="mb-0"><?= count($data['products']) ?></h4>
                            <div>Tổng Số Mặt Hàng</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <?php 
                                $lowStock = array_filter($data['products'], function($p) {
                                    return $p['soluong'] <= 10;
                                });
                            ?>
                            <h4 class="mb-0"><?= count($lowStock) ?></h4>
                            <div>Sắp Hết Hàng</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <?php 
                                $totalValue = array_reduce($data['products'], function($carry, $p) {
                                    return $carry + ($p['soluong'] * $p['giaNhap']);
                                }, 0);
                            ?>
                            <h4 class="mb-0"><?= number_format($totalValue) ?> ₫</h4>
                            <div>Tổng Giá Trị Tồn Kho</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">
                            <?php 
                                $totalSold = array_reduce($data['products'], function($carry, $p) {
                                    return $carry + ($p['sold_count'] ?? 0);
                                }, 0);
                            ?>
                            <h4 class="mb-0"><?= number_format($totalSold) ?></h4>
                            <div>Tổng Số Lượng Đã Bán</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#inventoryTable').DataTable({
        "language": {
            "lengthMenu": "Hiển thị _MENU_ dòng mỗi trang",
            "zeroRecords": "Không tìm thấy dữ liệu",
            "info": "Trang _PAGE_ / _PAGES_",
            "infoEmpty": "Không có dữ liệu",
            "infoFiltered": "(lọc từ _MAX_ dòng)",
            "search": "Tìm kiếm:",
            "paginate": {
                "first": "Đầu",
                "last": "Cuối",
                "next": "Sau",
                "previous": "Trước"
            }
        },
        "order": [[3, "asc"]] // Sắp xếp theo tồn kho tăng dần
    });
});
</script>