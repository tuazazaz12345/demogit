<?php
// Kiểm tra dữ liệu sản phẩm
if (isset($data['product'])) {
    $product = $data['product'];
} else {
    echo "<h3>Không tìm thấy sản phẩm!</h3>";
    return;
}

// Lấy messages từ data
$error_message = $data['error_message'] ?? null;
$success_message = $data['success_message'] ?? null;
$masp = $data['masp'] ?? null;
$reviews = $data['reviews'] ?? [];
$average = $data['average'] ?? 0;
?>

<!-- Hiển thị messages -->
<?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Lỗi!</strong> <?= htmlspecialchars($error_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Thành công!</strong> <?= htmlspecialchars($success_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Thông tin sản phẩm -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-5">
            <div class="product-image-container border rounded p-3 text-center bg-white shadow-sm">
                <img src="/phpnangcao/MVC/public/images/<?= htmlspecialchars($product['hinhanh']) ?>" 
                     alt="<?= htmlspecialchars($product['tensp']) ?>" 
                     class="img-fluid product-image" 
                     style="max-height: 400px; object-fit: contain;">
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="product-details p-3">
                <h2 class="mb-3"><?= htmlspecialchars($product['tensp']) ?></h2>
                <div class="price-section mb-4">
                    <h3 class="text-danger mb-2"><?= number_format($product['giaXuat']) ?> VNĐ</h3>
                    <?php if(isset($product['khuyenmai']) && $product['khuyenmai'] > 0): ?>
                        <span class="badge bg-danger">-<?= $product['khuyenmai'] ?>%</span>
                    <?php endif; ?>
                </div>
                
                <div class="description-section mb-4">
                    <h5>Mô tả sản phẩm:</h5>
                    <div class="product-description">
                        <div class="description-content">
                            <div class="description-text" style="white-space: pre-line;">
                                <?= htmlspecialchars($product['mota']) ?>
                            </div>
                        </div>
                        <button type="button" class="btn btn-link read-more p-0" 
                                style="display: none;">Đọc thêm</button>
                    </div>
                </div>

                <style>
                    .description-content {
                        position: relative;
                        max-height: 150px;
                        overflow: hidden;
                        transition: max-height 0.5s ease;
                    }
                    .description-content.expanded {
                        max-height: none;
                    }
                    .description-content:not(.expanded)::after {
                        content: '';
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        width: 100%;
                        height: 70px;
                        background: linear-gradient(transparent, white);
                        pointer-events: none;
                    }
                    .description-text {
                        margin-bottom: 1rem;
                    }
                </style>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const content = document.querySelector('.description-content');
                    const text = document.querySelector('.description-text');
                    const button = document.querySelector('.read-more');
                    
                    // Chỉ hiển thị nút "Đọc thêm" nếu nội dung bị cắt
                    if (text.offsetHeight > 150) {
                        button.style.display = 'block';
                        
                        button.addEventListener('click', function() {
                            if (content.classList.contains('expanded')) {
                                content.classList.remove('expanded');
                                this.textContent = 'Đọc thêm';
                                // Cuộn lên đầu phần mô tả
                                content.scrollIntoView({behavior: 'smooth'});
                            } else {
                                content.classList.add('expanded');
                                this.textContent = 'Thu gọn';
                            }
                        });
                    }
                });
                </script>

                <script>
                function toggleDescription() {
                    const content = document.querySelector('.description-content');
                    const button = document.querySelector('.read-more');
                    
                    if (content.classList.contains('collapsed')) {
                        content.classList.remove('collapsed');
                        content.classList.add('expanded');
                        button.textContent = 'Thu gọn';
                    } else {
                        content.classList.remove('expanded');
                        content.classList.add('collapsed');
                        button.textContent = 'Đọc thêm';
                    }
                }
                </script>

                <!-- Thêm form số lượng và nút thêm vào giỏ hàng -->
                <form action="<?= APP_URL ?>/Home/addtocard" method="POST" class="mb-4">
                    <input type="hidden" name="masp" value="<?= htmlspecialchars($product['masp']) ?>">
                    <input type="hidden" name="from_promotion" value="false">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="input-group" style="width: 150px;">
                                <span class="input-group-text">Số lượng</span>
                                <input type="number" name="quantity" class="form-control" value="1" min="1">
                            </div>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                            <a href="<?= APP_URL ?>/Home/order" class="btn btn-success">
                                <i class="fas fa-shopping-cart"></i> Xem giỏ hàng
                            </a>
                            <button type="button" class="btn btn-outline-danger" id="wishlistBtn" onclick="toggleWishlist('<?= htmlspecialchars($product['masp']) ?>')">
                                <i class="bi bi-heart"></i> <span id="wishlistBtnText">Thêm yêu thích</span>
                            </button>
                        </div>
                    </div>
                </form>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    checkWishlistStatus('<?= htmlspecialchars($product['masp']) ?>');
                });

                function toggleWishlist(masp) {
                    const btn = document.getElementById('wishlistBtn');
                    const isFilled = btn.classList.contains('btn-danger');
                    
                    if (!<?= isset($_SESSION['user']) ? 'true' : 'false' ?>) {
                        window.location.href = '<?= APP_URL ?>/AuthController/ShowLogin';
                        return;
                    }

                    fetch('<?= APP_URL ?>/WishlistController/' + (isFilled ? 'remove' : 'add'), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'masp=' + masp
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            checkWishlistStatus(masp);
                            showNotification(data.message, 'success');
                        } else {
                            showNotification(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('Lỗi: ' + error, 'error');
                    });
                }

                function checkWishlistStatus(masp) {
                    fetch('<?= APP_URL ?>/WishlistController/checkExists', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'masp=' + masp
                    })
                    .then(response => response.json())
                    .then(data => {
                        const btn = document.getElementById('wishlistBtn');
                        const text = document.getElementById('wishlistBtnText');
                        if (data.exists) {
                            btn.classList.remove('btn-outline-danger');
                            btn.classList.add('btn-danger');
                            text.textContent = 'Đã yêu thích';
                        } else {
                            btn.classList.remove('btn-danger');
                            btn.classList.add('btn-outline-danger');
                            text.textContent = 'Thêm yêu thích';
                        }
                    });
                }

                function showNotification(message, type = 'info') {
                    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                    const alert = document.createElement('div');
                    alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
                    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                    alert.innerHTML = `
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    setTimeout(() => {
                        alert.remove();
                    }, 3000);
                }
                </script>

    <hr>

                <!-- ĐÁNH GIÁ SẢN PHẨM -->
                <div class="reviews-section mt-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h4 class="mb-0">Đánh giá sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <!-- Hiển thị điểm trung bình -->
                            <div class="rating-summary mb-4">
                                <?php if (isset($data['average']) && $data['average'] > 0): ?>
                                    <div class="d-flex align-items-center">
                                        <div class="h1 mb-0 me-3"><?= number_format($data['average'], 1) ?></div>
                                        <div>
                                            <div class="text-warning mb-1">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star<?= $i <= $data['average'] ? '' : '-o' ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <small class="text-muted">Dựa trên <?= count($data['reviews'] ?? []) ?> đánh giá</small>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                                <?php endif; ?>
                            </div>

                            <!-- Danh sách đánh giá cũ - DEPRECATED, xem cuối file -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            if ($relatedCount >= 4) break;
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

<!-- ==================== SECTION ĐÁNH GIÁ & BÌNH LUẬN ==================== -->
<div class="container mt-5 mb-5">
    <hr class="my-4">
    <h4 class="mb-4">
        <i class="fas fa-comments"></i> Đánh Giá & Bình Luận 
        <?php if (!empty($reviews)): ?>
            <span class="badge bg-primary"><?php echo count($reviews); ?></span>
        <?php endif; ?>
    </h4>

    <!-- Thống kê đánh giá -->
    <?php if (!empty($reviews)): ?>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center border-0 bg-light">
                    <div class="card-body">
                        <h5 class="card-title text-warning">
                            <?php for ($i = 0; $i < 5; $i++) { ?>
                                <i class="fas fa-star"></i>
                            <?php } ?>
                        </h5>
                        <p class="mb-0">
                            <strong><?php echo number_format($average, 1); ?>/5</strong>
                        </p>
                        <small class="text-muted"><?php echo count($reviews); ?> đánh giá</small>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Danh sách đánh giá đã duyệt -->
    <?php if (!empty($reviews)): ?>
        <div class="reviews-list mb-4">
            <?php foreach ($reviews as $review): ?>
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0">
                                    <strong><?php echo htmlspecialchars($review['ten']); ?></strong>
                                </h6>
                                <small class="text-muted"><?php echo htmlspecialchars($review['email']); ?></small>
                            </div>
                            <small class="text-muted">
                                <?php echo date('d/m/Y', strtotime($review['ngaygui'])); ?>
                            </small>
                        </div>

                        <!-- Đánh giá sao -->
                        <div class="mb-2">
                            <span class="text-warning">
                                <?php for ($i = 0; $i < 5; $i++) { ?>
                                    <?php if ($i < $review['sosao']): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php } ?>
                            </span>
                            <span class="ms-2">
                                <strong><?php echo $review['sosao']; ?>/5</strong>
                            </span>
                        </div>

                        <!-- Nội dung -->
                        <p class="mb-0">
                            <?php echo nl2br(htmlspecialchars($review['noidung'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Chưa có đánh giá -->
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle"></i> Chưa có đánh giá nào. 
            <?php if (isset($_SESSION['user'])): ?>
                <a href="#" class="alert-link" data-bs-toggle="collapse" data-bs-target="#reviewFormSection">
                    Hãy là người đầu tiên đánh giá sản phẩm này
                </a>
            <?php else: ?>
                <a href="<?php echo APP_URL; ?>/AuthController/showLogin" class="alert-link">
                    Đăng nhập để đánh giá
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.reviews-list {
    max-height: 600px;
    overflow-y: auto;
}

.reviews-list::-webkit-scrollbar {
    width: 6px;
}

.reviews-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.reviews-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.reviews-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.rating-selector {
    display: flex;
    align-items: center;
    gap: 15px;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    font-size: 2.5rem;
    gap: 5px;
}

.star-input {
    display: none;
}

.star-label {
    cursor: pointer;
    color: #cccccc;
    transition: color 0.15s ease, transform 0.15s ease;
    font-size: 2.5rem;
}

.star-label:hover {
    color: #ffc107;
    transform: scale(1.1);
}

/* Checked state - color all labels to the right */
.star-input:checked ~ .star-label {
    color: #ffc107;
}

/* Reset colors for labels to the left of checked input */
.star-input:checked ~ .star-label ~ .star-label {
    color: #cccccc;
}

#starValue {
    min-width: 80px;
    font-weight: bold;
    color: #666;
}
</style>

<script>
// JavaScript cho review form đã bị xóa
</script>



