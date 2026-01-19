<div class="container-fluid">
    <h1 class="mt-4">Quản lý tin tức</h1>
    <div class="card mb-4">
        <div class="card-header">
            <a href="<?php echo APP_URL; ?>/Admin/createNews" class="btn btn-primary">Thêm tin tức mới</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Hình ảnh</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data["news"] as $news): ?>
                            <tr>
                                <td><?= $news['id'] ?></td>
                                <td><?= $news['title'] ?></td>
                                <td><img src="<?= $news['image'] ?>" width="100"></td>
                                <td><?= date('d/m/Y', strtotime($news['created_at'])) ?></td>
                                <td>
                                    <a href="<?php echo APP_URL; ?>/Admin/editNews/<?= $news['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                    <a href="<?php echo APP_URL; ?>/Admin/deleteNews/<?= $news['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa tin tức này?')">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>