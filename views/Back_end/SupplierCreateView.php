<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                    unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thêm nhà cung cấp mới</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo APP_URL; ?>/SupplierController/store">
                        <div class="mb-3">
                            <label for="ten_ncc" class="form-label">Tên nhà cung cấp *</label>
                            <input type="text" class="form-control" id="ten_ncc" name="ten_ncc" 
                                   placeholder="Nhập tên nhà cung cấp" required>
                        </div>

                        <div class="mb-3">
                            <label for="dia_chi" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="dia_chi" name="dia_chi" 
                                   placeholder="Nhập địa chỉ">
                        </div>

                        <div class="mb-3">
                            <label for="dien_thoai" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="dien_thoai" name="dien_thoai" 
                                   placeholder="Nhập số điện thoại">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Nhập email">
                        </div>

                        <div class="mb-3">
                            <label for="han_hop_dong" class="form-label">Hạn hợp đồng</label>
                            <input type="date" class="form-control" id="han_hop_dong" name="han_hop_dong">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="trang_thai" name="trang_thai" checked>
                            <label class="form-check-label" for="trang_thai">
                                Hoạt động
                            </label>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo APP_URL; ?>/SupplierController/index" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
