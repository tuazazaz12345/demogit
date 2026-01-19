<?php require_once "./views/adminPage.php" ?>
<div class="container-fluid">
    <h1 class="mt-4">Sửa tin tức</h1>
    <div class="card mb-4">
        <div class="card-body">
            <form action="<?php echo APP_URL; ?>/Admin/updateNews/<?= $data['news']['id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $data['news']['title'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required><?= $data['news']['content'] ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Hình ảnh hiện tại</label>
                    <img src="<?= $data['news']['image'] ?>" width="200" class="d-block mb-2">
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="text-muted">Chỉ upload ảnh mới nếu muốn thay đổi</small>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật tin tức</button>
            </form>
        </div>
    </div>
</div>