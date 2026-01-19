<!-- Sản Phẩm Liên Quan -->
<?php if (!empty($data['relatedProducts'])): ?>
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold text-primary mb-4">
                <i class="bi bi-box-seam-collection"></i> Sản Phẩm Liên Quan
            </h4>
        </div>
    </div>
    
    <div class="row g-3">
        <?php 
        $relatedCount = 0;
        foreach ($data['relatedProducts'] as $related): 
            if ($relatedCount >= 5) break;
            $relatedCount++;
        ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 text-center position-relative shadow-sm hover-scale transition-all">
                    <!-- Rating Badge -->
                    <?php if (isset($related['avg_rating']) && $related['avg_rating'] > 0): ?>
                        <div class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
                            <i class="bi bi-star-fill"></i> <?= number_format($related['avg_rating'], 1) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Sales Badge -->
                    <?php if (isset($related['sold_count']) && $related['sold_count'] > 0): ?>
                        <div class="badge bg-info position-absolute bottom-0 start-0 m-2">
                            <i class="bi bi-fire"></i> Bán: <?= $related['sold_count'] ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Product Image -->
                    <a href="<?php echo APP_URL; ?>/Home/detail/<?= $related['masp'] ?>" 
                       class="product-link">
                        <img src="<?php echo APP_URL; ?>/public/images/<?= htmlspecialchars($related['hinhanh']) ?>"
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($related['tensp']) ?>"
                             style="height: 180px; object-fit: contain; padding: 10px;">
                    </a>
                    
                    <!-- Product Info -->
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title text-truncate" style="font-size: 0.9rem;">
                            <?= htmlspecialchars(strlen($related['tensp']) > 30 ? substr($related['tensp'], 0, 30) . '...' : $related['tensp']) ?>
                        </h6>
                        
                        <p class="text-danger fw-bold mb-2" style="font-size: 1.1rem;">
                            <?= number_format($related['giaXuat'], 0, ',', '.') ?> ₫
                        </p>
                        
                        <?php if (!empty($related['mota'])): ?>
                            <p class="card-text small text-muted text-truncate mb-2">
                                <?= htmlspecialchars($related['mota']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <a href="<?php echo APP_URL; ?>/Home/detail/<?= $related['masp'] ?>" 
                               class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-eye"></i> Chi Tiết
                            </a>
                            <a href="<?php echo APP_URL; ?>/Home/addtocard/<?= $related['masp'] ?>" 
                               class="btn btn-sm btn-primary w-100 mt-2">
                                <i class="bi bi-cart-plus"></i> Thêm Giỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.hover-scale {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-scale:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.transition-all {
    transition: all 0.3s ease;
}

.product-link {
    text-decoration: none;
    color: inherit;
}

.product-link:hover img {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}
</style>
<?php endif; ?>
