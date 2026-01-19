<?php
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
                   value="<?php echo htmlspecialchars($data['keyword'] ?? ''); ?>"
                   required>
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<!-- Best Sellers Section -->
<?php if (!isset($data['showAllProducts'])): ?>
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
        <?php if (isset($data['isSearchResult']) && isset($data['keyword'])): ?>
            <h4 class="fw-bold text-primary">Kết quả cho: "<?= htmlspecialchars($data['keyword']) ?>"</h4>
        <?php elseif (isset($data['currentType'])): ?>
            <h4 class="fw-bold text-primary"><?= htmlspecialchars($data['currentType']['tenLoaiSP']) ?></h4>
        <?php else: ?>
            <h4 class="fw-bold text-primary"><?= isset($data['showAllProducts']) ? 'Tất Cả Sản Phẩm' : 'Sản Phẩm Mới' ?></h4>
        <?php endif; ?>
    </div>

    <?php if (!isset($data['showAllProducts']) && !empty($data['newProducts'])): ?>
        <!-- New Products & Best Selling Section -->
        <div class="mb-4">
            <div class="row g-3">
                <!-- New Products (Left) -->
                <div class="col-md-6">
                    <h5 class="text-primary mb-3"><i class="bi bi-clock-history"></i> Sản Phẩm Mới</h5>
                    <div class="row g-3">
                        <?php 
                        $newProduct = reset($data['newProducts']);
                        if ($newProduct):
                            $hasPromotion = isset($data['promotions']) ? 
                                array_filter($data['promotions'], fn($p) => 
                                    isset($p['product_id']) && $p['product_id'] === $newProduct['masp']
                                ) : [];
                            $promotion = !empty($hasPromotion) ? reset($hasPromotion) : null;
                            $promotionPrice = $promotion ? $newProduct['giaXuat'] * (1 - $promotion['discount_percent']/100) : $newProduct['giaXuat'];
                        ?>
                            <div class="col-12">
                                <div class="card h-100 text-center position-relative">
                                    <?php if ($promotion): ?>
                                        <div class="badge bg-danger position-absolute top-0 end-0 m-2">
                                            -<?= $promotion['discount_percent']; ?>%
                                        </div>
                                    <?php endif; ?>
                                    <a href="<?php echo APP_URL; ?>/Home/detail/<?= $newProduct['masp'] ?>">
                                        <img src="<?php echo APP_URL; ?>/public/images/<?= htmlspecialchars($newProduct['hinhanh']) ?>"
                                             class="card-img-top" alt="<?= htmlspecialchars($newProduct['tensp']) ?>"
                                             style="height: 250px; object-fit: contain;">
                                    </a>
                                    <div class="card-body">
                                        <h6 class="card-title"><?= htmlspecialchars($newProduct['tensp']) ?></h6>
                                        <?php if ($promotion): ?>
                                            <p class="mb-1">
                                                <span class="text-muted text-decoration-line-through small">
                                                    <?= number_format($newProduct['giaXuat'], 0, ',', '.') ?> ₫
                                                </span>
                                            </p>
                                            <p class="text-danger fw-bold mb-2">
                                                <?= number_format($promotionPrice, 0, ',', '.') ?> ₫
                                            </p>
                                        <?php else: ?>
                                            <p class="text-danger fw-bold mb-2"><?= number_format($newProduct['giaXuat'], 0, ',', '.') ?> ₫</p>
                                        <?php endif; ?>
                                        <?php if (!empty($newProduct['mota'])): ?>
                                            <p class="card-text small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 40px;">
                                                <?= htmlspecialchars($newProduct['mota']) ?>
                                            </p>
                                        <?php endif; ?>
                                        <a href="<?php echo APP_URL; ?>/Home/addtocard/<?= $newProduct['masp'] ?>" 
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-cart-plus"></i> Thêm giỏ hàng
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Best Selling Products (Right) -->
                <div class="col-md-6">
                    <h5 class="text-primary mb-3"><i class="bi bi-fire"></i> Sản Phẩm Bán Chạy</h5>
                    <div class="row g-3">
                        <?php 
                        $bestSellingProduct = !empty($data['bestSellingProducts']) ? reset($data['bestSellingProducts']) : null;
                        if ($bestSellingProduct):
                            $hasPromotion = isset($data['promotions']) ? 
                                array_filter($data['promotions'], fn($p) => 
                                    isset($p['product_id']) && $p['product_id'] === $bestSellingProduct['masp']
                                ) : [];
                            $promotion = !empty($hasPromotion) ? reset($hasPromotion) : null;
                            $promotionPrice = $promotion ? $bestSellingProduct['giaXuat'] * (1 - $promotion['discount_percent']/100) : $bestSellingProduct['giaXuat'];
                        ?>
                            <div class="col-12">
                                <div class="card h-100 text-center position-relative">
                                    <?php if ($promotion): ?>
                                        <div class="badge bg-danger position-absolute top-0 end-0 m-2">
                                            -<?= $promotion['discount_percent']; ?>%
                                        </div>
                                    <?php endif; ?>
                                    <div class="badge bg-success position-absolute top-0 start-0 m-2">
                                        🔥 Bán Chạy
                                    </div>
                                    <a href="<?php echo APP_URL; ?>/Home/detail/<?= $bestSellingProduct['masp'] ?>">
                                        <img src="<?php echo APP_URL; ?>/public/images/<?= htmlspecialchars($bestSellingProduct['hinhanh']) ?>"
                                             class="card-img-top" alt="<?= htmlspecialchars($bestSellingProduct['tensp']) ?>"
                                             style="height: 250px; object-fit: contain;">
                                    </a>
                                    <div class="card-body">
                                        <h6 class="card-title"><?= htmlspecialchars($bestSellingProduct['tensp']) ?></h6>
                                        <?php if ($promotion): ?>
                                            <p class="mb-1">
                                                <span class="text-muted text-decoration-line-through small">
                                                    <?= number_format($bestSellingProduct['giaXuat'], 0, ',', '.') ?> ₫
                                                </span>
                                            </p>
                                            <p class="text-danger fw-bold mb-2">
                                                <?= number_format($promotionPrice, 0, ',', '.') ?> ₫
                                            </p>
                                        <?php else: ?>
                                            <p class="text-danger fw-bold mb-2"><?= number_format($bestSellingProduct['giaXuat'], 0, ',', '.') ?> ₫</p>
                                        <?php endif; ?>
                                        <?php if (!empty($bestSellingProduct['mota'])): ?>
                                            <p class="card-text small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 40px;">
                                                <?= htmlspecialchars($bestSellingProduct['mota']) ?>
                                            </p>
                                        <?php endif; ?>
                                        <a href="<?php echo APP_URL; ?>/Home/addtocard/<?= $bestSellingProduct['masp'] ?>" 
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-cart-plus"></i> Thêm giỏ hàng
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info">Chưa có sản phẩm bán chạy</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<!-- Main Content -->
<div class="container py-4">
    <!-- Section Title -->
    <div class="text-center mb-4">
        <?php if (isset($data['isSearchResult']) && isset($data['keyword'])): ?>
            <h4 class="fw-bold text-primary">Kết quả cho: "<?= htmlspecialchars($data['keyword']) ?>"</h4>
        <?php elseif (isset($data['currentType'])): ?>
            <h4 class="fw-bold text-primary"><?= htmlspecialchars($data['currentType']['tenLoaiSP']) ?></h4>
        <?php else: ?>
            <h4 class="fw-bold text-primary"><?= isset($data['showAllProducts']) ? 'Tất Cả Sản Phẩm' : 'Danh Sách Sản Phẩm' ?></h4>
        <?php endif; ?>
    </div>

    <?php if (false): ?>
        <!-- This section is removed as products are now shown in 2-column layout above -->
    <?php endif; ?>

    <?php if (isset($data['showAllProducts']) || empty($data['newProducts'])): ?>
        <!-- Filter and Sort Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm filter-panel">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-funnel"></i> Bộ Lọc & Sắp Xếp</h6>
                    </div>
                    <div class="card-body">
                        <form id="filterForm" method="GET" class="row g-3">
                            <!-- Price Range Filter -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">💰 Lọc Theo Giá</label>
                                <div class="price-range">
                                    <div class="mb-2">
                                        <label class="form-label small">Giá tối thiểu:</label>
                                        <input type="number" id="minPrice" name="minPrice" 
                                               class="form-control form-control-sm" 
                                               value="<?= $data['filterMinPrice'] ?? 0 ?>"
                                               min="0"
                                               placeholder="Từ 0 trở lên">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Giá tối đa:</label>
                                        <input type="number" id="maxPrice" name="maxPrice" 
                                               class="form-control form-control-sm" 
                                               value="<?= $data['filterMaxPrice'] ?? 999999999 ?>"
                                               min="0"
                                               placeholder="Bất kỳ giá nào">
                                    </div>
                                    <small class="text-muted">
                                        💡 Để trống để không giới hạn
                                    </small>
                                </div>
                            </div>

                            <!-- Sort Options -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">📊 Sắp Xếp Theo</label>
                                <select name="sort" id="sortSelect" class="form-select form-select-sm">
                                    <option value="price_asc" <?= ($data['sortBy'] ?? 'price_asc') === 'price_asc' ? 'selected' : '' ?>>
                                        💵 Giá: Thấp → Cao
                                    </option>
                                    <option value="price_desc" <?= ($data['sortBy'] ?? '') === 'price_desc' ? 'selected' : '' ?>>
                                        💵 Giá: Cao → Thấp
                                    </option>
                                    <option value="popularity" <?= ($data['sortBy'] ?? '') === 'popularity' ? 'selected' : '' ?>>
                                        🔥 Độ Phổ Biến (Bán Chạy)
                                    </option>
                                    <option value="rating" <?= ($data['sortBy'] ?? '') === 'rating' ? 'selected' : '' ?>>
                                        ⭐ Đánh Giá Cao Nhất
                                    </option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm me-2 flex-grow-1">
                                    <i class="bi bi-search"></i> Lọc & Sắp Xếp
                                </button>
                                <a href="<?= isset($data['currentType']) ? APP_URL . '/Home/showByType/' . $data['currentType']['maLoaiSP'] : 
                                          (isset($data['isSearchResult']) ? APP_URL . '/SearchController/index?keyword=' . urlencode($data['keyword'] ?? '') : 
                                          APP_URL . '/Home/showAllProducts') ?>" 
                                   class="btn btn-secondary btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Đặt Lại
                                </a>
                            </div>

                            <!-- Hidden inputs for pagination and search -->
                            <input type="hidden" name="page" id="pageInput" value="1">
                            <?php if (isset($data['keyword'])): ?>
                                <input type="hidden" name="keyword" value="<?= htmlspecialchars($data['keyword']) ?>">
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Regular Products Grid -->
        <div class="row g-3">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): 
                    $hasPromotion = isset($data['promotions']) ? 
                        array_filter($data['promotions'], fn($p) => 
                            isset($p['product_id']) && $p['product_id'] === $product['masp']
                        ) : [];
                    $promotion = !empty($hasPromotion) ? reset($hasPromotion) : null;
                    $promotionPrice = $promotion ? $product['giaXuat'] * (1 - $promotion['discount_percent']/100) : $product['giaXuat'];
                    
                    // Lấy đánh giá sản phẩm
                    $avgRating = $product['avg_rating'] ?? 0;
                    $soldCount = $product['sold_count'] ?? 0;
                ?>
                    <div class="col-6 col-md-3">
                        <div class="card h-100 text-center position-relative product-card">
                            <?php if ($promotion): ?>
                                <div class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    -<?= $promotion['discount_percent']; ?>%
                                </div>
                            <?php endif; ?>
                            
                            <!-- Star Rating Badge -->
                            <?php if ($avgRating > 0): ?>
                                <div class="badge bg-warning position-absolute top-0 start-0 m-2">
                                    ⭐ <?= number_format($avgRating, 1) ?>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php echo APP_URL; ?>/Home/detail/<?= $product['masp'] ?>">
                                <img src="<?php echo APP_URL; ?>/public/images/<?= htmlspecialchars($product['hinhanh']) ?>"
                                     class="card-img-top" alt="<?= htmlspecialchars($product['tensp']) ?>"
                                     style="height: 180px; object-fit: contain;">
                            </a>
                            <div class="card-body">
                                <h6 class="card-title text-truncate"><?= htmlspecialchars($product['tensp']) ?></h6>
                                
                                <!-- Rating Stars -->
                                <div class="rating-stars small mb-2">
                                    <?php 
                                        $stars = round($avgRating);
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $stars ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-muted"></i>';
                                        }
                                    ?>
                                    <?php if ($soldCount > 0): ?>
                                        <span class="text-muted small ms-2">(<?= $soldCount ?> bán)</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($promotion): ?>
                                    <p class="mb-1">
                                        <span class="text-muted text-decoration-line-through small">
                                            <?= number_format($product['giaXuat'], 0, ',', '.') ?> ₫
                                        </span>
                                    </p>
                                    <p class="text-danger fw-bold mb-2">
                                        <?= number_format($promotionPrice, 0, ',', '.') ?> ₫
                                    </p>
                                <?php else: ?>
                                    <p class="text-danger fw-bold mb-2"><?= number_format($product['giaXuat'], 0, ',', '.') ?> ₫</p>
                                <?php endif; ?>
                                
                                <?php if (!empty($product['mota'])): ?>
                                    <p class="card-text small mb-2 text-truncate">
                                        <?= htmlspecialchars($product['mota']) ?>
                                    </p>
                                <?php endif; ?>
                                <a href="<?php echo APP_URL; ?>/Home/addtocard/<?= $product['masp'] ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-cart-plus"></i> Thêm giỏ hàng
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Pagination -->
                <?php if (isset($data['totalPages']) && $data['totalPages'] > 1): ?>
                    <div class="col-12">
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php 
                                $baseUrl = isset($data['keyword']) ? APP_URL . '/SearchController/index?keyword=' . urlencode($data['keyword']) : 
                                          (isset($data['currentType']) ? APP_URL . '/Home/showByType/' . $data['currentType']['maLoaiSP'] . '?' : 
                                          APP_URL . '/Home/showAllProducts?');
                                $separator = strpos($baseUrl, '?') !== false ? '&' : '?';
                                $sortParam = isset($data['sortBy']) && $data['sortBy'] !== 'price_asc' ? '&sort=' . $data['sortBy'] : '';
                                $priceParams = (isset($data['filterMinPrice']) && $data['filterMinPrice'] > 0 ? '&minPrice=' . $data['filterMinPrice'] : '') .
                                             (isset($data['filterMaxPrice']) && $data['filterMaxPrice'] < 999999999 ? '&maxPrice=' . $data['filterMaxPrice'] : '');
                                ?>
                                <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                                    <li class="page-item <?= $i == ($data['currentPage'] ?? 1) ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= $baseUrl . $separator . 'page=' . $i . $sortParam . $priceParams ?>"><?= $i ?></a>
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
/* Sakura Theme Customizations */
.bg-light {
    background: linear-gradient(135deg, var(--sakura-pink) 0%, var(--sakura-bg) 100%) !important;
}

.card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    border-radius: 15px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(255, 77, 148, 0.2);
}

.filter-panel {
    background: rgba(255, 250, 250, 0.98);
    border: 1px solid rgba(255, 77, 148, 0.2);
}

.filter-panel .card-header {
    background: linear-gradient(45deg, var(--sakura-accent) 30%, #ff85b6 100%) !important;
}

.section-title {
    position: relative;
    display: inline-block;
    margin-bottom: 2rem;
}

.btn-primary, .btn-outline-primary:hover {
    background: linear-gradient(45deg, var(--sakura-accent) 30%, #ff85b6 100%);
    border: none;
    transition: transform 0.2s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 77, 148, 0.3);
}

.pagination .page-link:hover {
    background-color: var(--sakura-pink);
}

.form-control-sm, .form-select-sm {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 0.375rem 0.75rem;
}

.form-control-sm:focus, .form-select-sm:focus {
    border-color: var(--sakura-accent);
    box-shadow: 0 0 0 0.2rem rgba(255, 77, 148, 0.25);
}

.rating-stars {
    font-size: 0.9rem;
}

.price-range input {
    max-width: 100%;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: var(--sakura-bg);
}

::-webkit-scrollbar-thumb {
    background: var(--sakura-accent);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #ff3385;
}
</style>

<script>
// Xử lý form filter
document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate giá
    const minPrice = parseInt(document.getElementById('minPrice').value) || 0;
    const maxPrice = parseInt(document.getElementById('maxPrice').value) || 999999999;
    
    if (minPrice > maxPrice) {
        alert('Giá tối thiểu không được lớn hơn giá tối đa!');
        return;
    }
    
    // Submit form
    this.submit();
});

// Sakura petals animation
function createSakuraPetal() {
    const petal = document.createElement('div');
    petal.className = 'sakura';
    
    const size = Math.random() * 10 + 10; // 10-20px
    petal.style.width = `${size}px`;
    petal.style.height = `${size}px`;
    petal.style.background = `rgba(255, ${Math.random() * 20 + 215}, ${Math.random() * 20 + 235}, ${Math.random() * 0.3 + 0.7})`;
    petal.style.borderRadius = '100% 0 100% 0';
    petal.style.position = 'fixed';
    petal.style.pointerEvents = 'none';
    petal.style.left = `${Math.random() * 100}%`;
    petal.style.transform = `rotate(${Math.random() * 360}deg)`;
    
    document.body.appendChild(petal);
    
    const animation = petal.animate([
        { transform: `translate(0, -10px) rotate(${Math.random() * 360}deg)`, opacity: 1 },
        { transform: `translate(${Math.random() * 100 - 50}px, ${window.innerHeight}px) rotate(${Math.random() * 360}deg)`, opacity: 0 }
    ], {
        duration: Math.random() * 3000 + 3000,
        easing: 'cubic-bezier(0.37, 0, 0.63, 1)'
    });
    
    animation.onfinish = () => petal.remove();
}

// Create petals periodically
setInterval(createSakuraPetal, 300);

// Initial burst of petals
for (let i = 0; i < 10; i++) {
    setTimeout(createSakuraPetal, Math.random() * 1000);
}
</script>
</div>

<style>
/* Sakura Theme Customizations */
.bg-light {
    background: linear-gradient(135deg, var(--sakura-pink) 0%, var(--sakura-bg) 100%) !important;
}

.card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(5px);
    border-radius: 15px;
}

.section-title {
    position: relative;
    display: inline-block;
    margin-bottom: 2rem;
}

.btn-primary, .btn-outline-primary:hover {
    background: linear-gradient(45deg, var(--sakura-accent) 30%, #ff85b6 100%);
    border: none;
    transition: transform 0.2s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 77, 148, 0.3);
}

.pagination .page-link:hover {
    background-color: var(--sakura-pink);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: var(--sakura-bg);
}

::-webkit-scrollbar-thumb {
    background: var(--sakura-accent);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #ff3385;
}
</style>

<script>
// Sakura petals animation
function createSakuraPetal() {
    const petal = document.createElement('div');
    petal.className = 'sakura';
    
    const size = Math.random() * 10 + 10; // 10-20px
    petal.style.width = `${size}px`;
    petal.style.height = `${size}px`;
    petal.style.background = `rgba(255, ${Math.random() * 20 + 215}, ${Math.random() * 20 + 235}, ${Math.random() * 0.3 + 0.7})`;
    petal.style.borderRadius = '100% 0 100% 0';
    petal.style.position = 'fixed';
    petal.style.pointerEvents = 'none';
    petal.style.left = `${Math.random() * 100}%`;
    petal.style.transform = `rotate(${Math.random() * 360}deg)`;
    
    document.body.appendChild(petal);
    
    const animation = petal.animate([
        { transform: `translate(0, -10px) rotate(${Math.random() * 360}deg)`, opacity: 1 },
        { transform: `translate(${Math.random() * 100 - 50}px, ${window.innerHeight}px) rotate(${Math.random() * 360}deg)`, opacity: 0 }
    ], {
        duration: Math.random() * 3000 + 3000,
        easing: 'cubic-bezier(0.37, 0, 0.63, 1)'
    });
    
    animation.onfinish = () => petal.remove();
}

// Create petals periodically
setInterval(createSakuraPetal, 300);

// Initial burst of petals
for (let i = 0; i < 10; i++) {
    setTimeout(createSakuraPetal, Math.random() * 1000);
}
</script>