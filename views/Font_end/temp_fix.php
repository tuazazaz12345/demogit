<?php
// Reset the entire file content
file_put_contents('d:\xampp\htdocs\phpnangcao\MVC\views\Font_end\HomeView.php', '');

// Now create new content
$content = '<?php
$products = $data["productList"] ?? [];
$currentType = $data["currentType"] ?? null;
?>

<!-- Hero Section -->
<div class="bg-light py-4 mb-4 border-bottom">
    <div class="container text-center">
        <h1 class="fw-bold text-primary mb-2">Cửa Hàng Sách</h1>
        <p class="text-muted mb-3">Khám phá kho sách phong phú dành cho mọi lứa tuổi</p>
        <form class="d-flex justify-content-center" 
              action="<?php echo APP_URL; ?>/SearchController/index" method="GET"
              style="max-width: 500px; margin: 0 auto;">
            <input class="form-control me-2" type="search" name="keyword"
                   placeholder="Tìm kiếm sách..."
                   value="<?php echo htmlspecialchars($data[\'keyword\'] ?? \'\'); ?>"
                   required>
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<!-- Best Sellers Section -->
<?php if (!isset($data[\'showAllProducts\'])): ?>
<div class="best-sellers-widget" id="bestSellersWidget">
    <!-- Best Sellers Content -->
    <div id="bestSellersCarousel" class="carousel slide shadow" data-bs-ride="carousel">
        <!-- ... Best Sellers Carousel Content ... -->
    </div>
</div>
<?php endif; ?>

<!-- Main Content -->
<div class="container py-4">
    <!-- Section Title -->
    <div class="text-center mb-4">
        <?php if (isset($data[\'isSearchResult\']) && isset($data[\'keyword\'])): ?>
            <h4 class="fw-bold text-primary">Kết quả cho: "<?= htmlspecialchars($data[\'keyword\']) ?>"</h4>
        <?php elseif (isset($data[\'currentType\'])): ?>
            <h4 class="fw-bold text-primary"><?= htmlspecialchars($data[\'currentType\'][\'tenLoaiSP\']) ?></h4>
        <?php else: ?>
            <h4 class="fw-bold text-primary"><?= isset($data[\'showAllProducts\']) ? \'Tất Cả Sản Phẩm\' : \'Sản Phẩm Mới\' ?></h4>
        <?php endif; ?>
    </div>

    <?php if (!isset($data[\'showAllProducts\']) && !empty($data[\'newProducts\'])): ?>
        <!-- New Products Section -->
        <div class="mb-4">
            <h5 class="text-primary mb-3"><i class="bi bi-clock-history"></i> Sản Phẩm Mới</h5>
            <div class="row g-3">
                <?php foreach ($data[\'newProducts\'] as $product): ?>
                    <div class="col-6 col-md-4">
                        <div class="card h-100 text-center">
                            <!-- New Product Card Content -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($data[\'showAllProducts\']) || empty($data[\'newProducts\'])): ?>
        <!-- Regular Products Grid -->
        <div class="row g-3">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-6 col-md-3">
                        <div class="card h-100 text-center">
                            <a href="<?php echo APP_URL; ?>/Home/detail/<?= $product[\'masp\'] ?>">
                                <img src="<?php echo APP_URL; ?>/public/images/<?= htmlspecialchars($product[\'hinhanh\']) ?>"
                                     class="card-img-top" alt="<?= htmlspecialchars($product[\'tensp\']) ?>"
                                     style="height: 180px; object-fit: contain;">
                            </a>
                            <div class="card-body">
                                <h6 class="card-title text-truncate"><?= htmlspecialchars($product[\'tensp\']) ?></h6>
                                <p class="text-danger fw-bold mb-2"><?= number_format($product[\'giaXuat\'], 0, \',\', \'.\') ?> ₫</p>
                                <?php if (!empty($product[\'mota\'])): ?>
                                    <p class="card-text small mb-2 text-truncate">
                                        <?= htmlspecialchars($product[\'mota\']) ?>
                                    </p>
                                <?php endif; ?>
                                <a href="<?php echo APP_URL; ?>/Home/addtocard/<?= $product[\'masp\'] ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-cart-plus"></i> Thêm giỏ hàng
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Pagination -->
                <?php if (isset($data[\'totalPages\']) && $data[\'totalPages\'] > 1): ?>
                    <div class="col-12">
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $data[\'totalPages\']; $i++): ?>
                                    <li class="page-item <?= $i == ($data[\'currentPage\'] ?? 1) ? \'active\' : \'\' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Không có sản phẩm nào để hiển thị.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Your existing styles */
</style>

<script>
/* Your existing scripts */
</script>';

file_put_contents('d:\xampp\htdocs\phpnangcao\MVC\views\Font_end\HomeView.php', $content);
?>