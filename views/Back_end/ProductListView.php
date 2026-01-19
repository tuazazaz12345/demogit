
 <div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary mb-0">Quản lý sản phẩm</h3>
        <a href="<?= APP_URL ?>/Product/create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm sản phẩm
        </a>
    </div>

    <!-- Search Bar -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="<?= APP_URL ?>/Product/index" class="row g-3">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="search" placeholder="Tìm theo tên sản phẩm..." 
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="code" placeholder="Tìm theo mã sản phẩm..." 
                        value="<?= isset($_GET['code']) ? htmlspecialchars($_GET['code']) : '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="<?= APP_URL ?>/Product/index" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Danh sách sản phẩm</strong>
            <?php if (isset($_GET['search']) || isset($_GET['code'])): ?>
                <span class="badge bg-info ms-2">
                    Kết quả tìm kiếm
                </span>
            <?php endif; ?>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Ảnh</th>
                            <th>Mã SP</th>
                            <th>Tên SP</th>
                            <th>Loại</th>
                            <th>Số lượng</th>
                            <th>Giá nhập</th>
                            <th>Giá xuất</th>
                            <th>KM</th>
                            <th>Mô tả</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                     <?php
                        if (!empty($data['productList'])) {
                            $i = 1;
                            foreach ($data['productList'] as  $k => $v) {
                        ?>
                        <tr>
                            <td><?= $i++?></td>
                            <td>
                                <img src="<?php echo APP_URL;?>/public/images/<?= htmlspecialchars($v['hinhanh']) ?>" 
                                style="height: 10rem;"/>
                            </td>
                            <td>
                              <?= htmlspecialchars($v["masp"]) ?> 
                            </td>
                            <td>
                                <?= htmlspecialchars($v["tensp"]) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($v["maLoaiSP"]) ?>
                            </td>
                            <td><?= htmlspecialchars($v["soluong"]) ?></td>
                            <td><?= htmlspecialchars($v["giaNhap"]) ?> </td>
                            <td><?= htmlspecialchars($v["giaXuat"]) ?></td>
                            <td><?= htmlspecialchars($v["khuyenmai"]) ?></td>
                            <td>
                                <div class="description-preview">
                                    <?php
                                    $mota = htmlspecialchars($v["mota"]);
                                    $shortDesc = mb_strlen($mota) > 100 ? mb_substr($mota, 0, 100) . '...' : $mota;
                                    ?>
                                    <span class="short-desc"><?= $shortDesc ?></span>
                                    <?php if (mb_strlen($mota) > 100): ?>
                                        <div class="full-desc" style="display: none;">
                                            <?= $mota ?>
                                        </div>
                                        <button type="button" class="btn btn-link btn-sm toggle-desc p-0" 
                                                onclick="toggleDescription(this)">
                                            Xem thêm
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($v["createDate"]) ?></td>
                            <td>
                                <a href="<?= APP_URL ?>/Product/edit/<?= $v["masp"] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                                <a href="<?= APP_URL ?>/Product/delete/<?= $v["masp"] ?>" class="btn btn-danger btn-sm"
                                 onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?');">🗑️ Xoá</a>
                            </td>
                        </tr>
                        <?php } 
                        } else {
                        ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                Không có sản phẩm nào.
                            </td>
                        </tr>
                        <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.description-preview {
    max-width: 300px;
    position: relative;
}

.full-desc {
    position: absolute;
    left: 0;
    background: white;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    z-index: 1000;
    max-width: 400px;
    white-space: pre-line;
}

.toggle-desc {
    font-size: 0.8rem;
    text-decoration: none;
}
</style>

<script>
function toggleDescription(button) {
    const preview = button.parentElement;
    const fullDesc = preview.querySelector('.full-desc');
    const isVisible = fullDesc.style.display !== 'none';
    
    // Ẩn tất cả các full-desc khác
    document.querySelectorAll('.full-desc').forEach(desc => {
        if (desc !== fullDesc) {
            desc.style.display = 'none';
        }
    });
    
    // Toggle description hiện tại
    fullDesc.style.display = isVisible ? 'none' : 'block';
    button.textContent = isVisible ? 'Xem thêm' : 'Thu gọn';
    
    // Click outside to close
    if (!isVisible) {
        document.addEventListener('click', function closeDesc(e) {
            if (!preview.contains(e.target)) {
                fullDesc.style.display = 'none';
                button.textContent = 'Xem thêm';
                document.removeEventListener('click', closeDesc);
            }
        });
    }
}
</script>