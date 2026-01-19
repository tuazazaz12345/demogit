<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<div class="container mt-4">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">📦 Quản lý danh mục loại sản phẩm</h4>
        </div>
        <div class="card-body">
            <!-- Form thêm/sửa -->
            <?php
            $isEdit = isset($data["editItem"]);
            $item = $isEdit ? $data["editItem"] : null;
            ?>
            <form action="<?= $isEdit ? APP_URL . '/ProductType/update/' . $item['maLoaiSP'] : APP_URL . '/ProductType/create' ?>" 
                  method="POST" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Mã loại sản phẩm</label>
                    <input type="text" name="txt_maloaisp" class="form-control" 
                           value="<?= $isEdit ? htmlspecialchars($item['maLoaiSP']) : '' ?>"
                           <?= $isEdit ? 'readonly' : '' ?> required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tên loại sản phẩm</label>
                    <input type="text" name="txt_tenloaisp" class="form-control"
                           value="<?= $isEdit ? htmlspecialchars($item['tenLoaiSP']) : '' ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mô tả</label>
                    <input type="text" name="txt_motaloaisp" class="form-control"
                           value="<?= $isEdit ? htmlspecialchars($item['moTaLoaiSP']) : '' ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-<?= $isEdit ? 'warning' : 'primary' ?>">
                        <?= $isEdit ? '♻️ Cập nhật' : '➕ Thêm mới' ?>
                    </button>
                    <?php if ($isEdit): ?>
                        <a href="<?= APP_URL ?>/ProductType" class="btn btn-secondary">❌ Hủy</a>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Danh sách loại sản phẩm -->
            <div class="table-responsive">
                <?php if (!empty($data["productList"])): ?>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">STT</th>
                                <th width="20%">Mã loại</th>
                                <th width="30%">Tên loại</th>
                                <th width="30%">Mô tả</th>
                                <th width="15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data["productList"] as $index => $item): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($item["maLoaiSP"]) ?></td>
                                <td><?= htmlspecialchars($item["tenLoaiSP"]) ?></td>
                                <td><?= htmlspecialchars($item["moTaLoaiSP"]) ?></td>
                                <td>
                                    <a href="<?= APP_URL ?>/ProductType/edit/<?= $item["maLoaiSP"] ?>" 
                                       class="btn btn-warning btn-sm">
                                        ✏️ Sửa
                                    </a>
                                    <a href="<?= APP_URL ?>/ProductType/delete/<?= $item["maLoaiSP"] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Bạn có chắc muốn xóa loại sản phẩm này?');">
                                        🗑️ Xóa
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info mt-3">
                        Chưa có loại sản phẩm nào. Vui lòng thêm mới.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Toast thông báo -->
<?php if (isset($_SESSION['message'])): ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show align-items-center text-white <?= $_SESSION['message_type'] == 'success' ? 'bg-success' : 'bg-danger' ?>" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <?= $_SESSION['message'] ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<?php
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
endif;
?>