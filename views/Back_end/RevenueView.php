<div class="container-fluid px-4">
    <!-- Tiêu đề trang và thống kê tổng quan -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2 class="mb-0">Thống Kê Doanh Thu</h2>
        <div class="d-flex gap-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <small class="text-muted">Tổng Doanh Thu</small>
                    <h4 class="mb-0"><?= number_format(array_sum(array_column($data['revenueData'], 'total_revenue'))) ?> ₫</h4>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-bag"></i>
                </div>
                <div>
                    <small class="text-muted">Tổng Đơn Hàng</small>
                    <h4 class="mb-0"><?= number_format(array_sum(array_column($data['revenueData'], 'order_count'))) ?></h4>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form lọc -->
    <div class="admin-card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Thời Gian</label>
                    <select name="period" class="form-select" onchange="this.form.submit()">
                        <option value="day" <?= $data['period'] == 'day' ? 'selected' : '' ?>>Theo ngày</option>
                        <option value="month" <?= $data['period'] == 'month' ? 'selected' : '' ?>>Theo tháng</option>
                        <option value="year" <?= $data['period'] == 'year' ? 'selected' : '' ?>>Theo năm</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $data['startDate'] ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $data['endDate'] ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-custom d-block w-100">
                        <i class="bi bi-funnel me-2"></i>Lọc dữ liệu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="admin-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>Biểu Đồ Doanh Thu
                    </h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary active" onclick="updateChartType('bar')">
                            <i class="bi bi-bar-chart"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="updateChartType('line')">
                            <i class="bi bi-graph-up"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="admin-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Tỷ Lệ Doanh Thu
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="revenuePieChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng chi tiết -->
    <div class="admin-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">
                <i class="bi bi-table me-2"></i>Chi Tiết Doanh Thu
            </h5>
            <button class="btn btn-sm btn-custom" onclick="exportToExcel()">
                <i class="bi bi-download me-2"></i>Xuất Excel
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="revenueTable">
                <thead>
                    <tr>
                        <th>Thời Gian</th>
                        <th class="text-center">Số Đơn Hàng</th>
                        <th class="text-end">Doanh Thu</th>
                        <th class="text-end">Tỷ Lệ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalRevenue = array_sum(array_column($data['revenueData'], 'total_revenue'));
                    foreach ($data['revenueData'] as $row): 
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['period']) ?></td>
                            <td class="text-center"><?= number_format($row['order_count']) ?></td>
                            <td class="text-end"><?= number_format($row['total_revenue']) ?> ₫</td>
                            <td class="text-end"><?= number_format(($row['total_revenue'] / $totalRevenue) * 100, 1) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-primary fw-bold">
                        <td>Tổng cộng</td>
                        <td class="text-center"><?= number_format(array_sum(array_column($data['revenueData'], 'order_count'))) ?></td>
                        <td class="text-end"><?= number_format($totalRevenue) ?> ₫</td>
                        <td class="text-end">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Sản phẩm Bán Chạy -->
    <div class="row g-4 mt-3">
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-fire me-2 text-danger"></i>Sản Phẩm Bán Chạy
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Tên Sản Phẩm</th>
                                <th class="text-center">Số Lượng Bán</th>
                                <th class="text-end">Doanh Thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($data['topProducts']) && is_array($data['topProducts'])): ?>
                                <?php foreach ($data['topProducts'] as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['tensp']) ?></td>
                                        <td class="text-center"><span class="badge bg-danger"><?= number_format($product['quantity_sold']) ?></span></td>
                                        <td class="text-end"><?= number_format($product['total_revenue'] ?? 0) ?> ₫</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">Chưa có dữ liệu</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sản phẩm Bán Chậm -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-exclamation-circle me-2 text-warning"></i>Sản Phẩm Bán Chậm
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Tên Sản Phẩm</th>
                                <th class="text-center">Số Lượng Bán</th>
                                <th class="text-end">Doanh Thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($data['slowProducts']) && is_array($data['slowProducts'])): ?>
                                <?php foreach ($data['slowProducts'] as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['tensp']) ?></td>
                                        <td class="text-center"><span class="badge bg-warning text-dark"><?= number_format($product['quantity_sold']) ?></span></td>
                                        <td class="text-end"><?= number_format($product['total_revenue'] ?? 0) ?> ₫</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">Chưa có dữ liệu</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tăng Trưởng Doanh Thu -->
    <div class="admin-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">
                <i class="bi bi-graph-up-arrow me-2 text-success"></i>Tăng Trưởng Doanh Thu
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kỳ</th>
                        <th class="text-end">Doanh Thu</th>
                        <th class="text-center">Số Đơn</th>
                        <th class="text-end">Tăng Trưởng</th>
                        <th class="text-center">Biểu Đồ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($data['growthData']) && is_array($data['growthData'])): ?>
                        <?php foreach ($data['growthData'] as $item): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($item['period']) ?></strong></td>
                                <td class="text-end"><?= number_format($item['revenue']) ?> ₫</td>
                                <td class="text-center"><?= number_format($item['order_count']) ?></td>
                                <td class="text-end">
                                    <?php if ($item['growth'] > 0): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-up"></i> +<?= number_format($item['growth'], 1) ?>%
                                        </span>
                                    <?php elseif ($item['growth'] < 0): ?>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-arrow-down"></i> <?= number_format($item['growth'], 1) ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">0%</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div style="width: 50px; height: 20px; background: linear-gradient(to right, #e3f2fd, #1976d2); border-radius: 3px;"></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">Chưa có dữ liệu</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Dữ liệu cho biểu đồ
const revenueData = <?= json_encode($data['revenueData']) ?>;
let revenueChart;

// Hàm khởi tạo biểu đồ doanh thu
function initRevenueChart(type = 'bar') {
    const ctx = document.getElementById('revenueChart');
    
    // Hủy biểu đồ cũ nếu tồn tại
    if (revenueChart) {
        revenueChart.destroy();
    }

    // Khởi tạo biểu đồ mới
    revenueChart = new Chart(ctx, {
        type: type,
        data: {
            labels: revenueData.map(item => item.period),
            datasets: [{
                label: 'Doanh thu',
                data: revenueData.map(item => item.total_revenue),
                backgroundColor: type === 'bar' ? 'rgba(37, 99, 235, 0.7)' : 'rgba(37, 99, 235, 0.1)',
                borderColor: '#2563eb',
                borderWidth: 2,
                fill: type === 'line',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' ₫';
                        }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// Hàm cập nhật loại biểu đồ
function updateChartType(type) {
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    initRevenueChart(type);
}

// Biểu đồ tròn
const pieCtx = document.getElementById('revenuePieChart');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: revenueData.map(item => item.period),
        datasets: [{
            data: revenueData.map(item => item.total_revenue),
            backgroundColor: [
                'rgba(37, 99, 235, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        },
        cutout: '70%'
    }
});

// Khởi tạo biểu đồ mặc định
initRevenueChart();
</script>

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.17.0/dist/xlsx.full.min.js"></script>

<script>
// Khởi tạo DataTable
$(document).ready(function() {
    $('#revenueTable').DataTable({
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
        "pageLength": 10,
        "order": [[0, "desc"]],
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
});

// Hàm xuất Excel
function exportToExcel() {
    const table = document.getElementById('revenueTable');
    const wb = XLSX.utils.table_to_book(table, {sheet: "Doanh Thu"});
    const wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'});

    function s2ab(s) {
        const buf = new ArrayBuffer(s.length);
        const view = new Uint8Array(buf);
        for (let i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
    }

    const blob = new Blob([s2ab(wbout)], {type: 'application/octet-stream'});
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'Thong_ke_doanh_thu_' + new Date().toISOString().split('T')[0] + '.xlsx';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>