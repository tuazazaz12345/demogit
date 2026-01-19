<div class="container mt-4">
    <h2>Tin tức</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($data["news"] as $news): ?>
            <div class="col">
                <div class="card h-100">
                    <?php if (!empty($news['image'])): ?>
                        <img src="<?php echo APP_URL . '/' . $news['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($news['title']); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h5>
                        <p class="card-text"><?php echo substr(strip_tags($news['content']), 0, 150); ?>...</p>
                        <a href="<?php echo APP_URL; ?>/NewsController/detail/<?php echo $news['id']; ?>" class="btn btn-primary">Đọc thêm</a>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Ngày đăng: <?php echo date('d/m/Y', strtotime($news['created_at'])); ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>