<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/NewsController/showNews">Tin tức</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($data['news']['title']); ?></li>
        </ol>
    </nav>
    
    <div class="card">
        <?php if (!empty($data['news']['image'])): ?>
            <img src="<?php echo APP_URL . '/' . $data['news']['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($data['news']['title']); ?>">
        <?php endif; ?>
        <div class="card-body">
            <h1 class="card-title"><?php echo htmlspecialchars($data['news']['title']); ?></h1>
            <p class="text-muted">Ngày đăng: <?php echo date('d/m/Y', strtotime($data['news']['created_at'])); ?></p>
            <div class="card-text">
                <?php echo nl2br(htmlspecialchars($data['news']['content'])); ?>
            </div>
        </div>
    </div>
</div>