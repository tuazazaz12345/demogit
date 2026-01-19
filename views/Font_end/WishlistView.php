<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra user đã login
if (!isset($_SESSION['user'])) {
    header("Location: " . APP_URL . "/AuthController/ShowLogin");
    exit;
}
?>

<style>
.wishlist-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 15px;
}

.wishlist-header {
    margin-bottom: 30px;
    border-bottom: 3px solid #E8A87C;
    padding-bottom: 20px;
}

.wishlist-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.wishlist-count {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
}

.wishlist-empty {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.wishlist-empty i {
    font-size: 64px;
    color: #ccc;
    margin-bottom: 20px;
}

.wishlist-empty p {
    color: #999;
    font-size: 16px;
    margin-bottom: 20px;
}

.wishlist-empty a {
    display: inline-block;
    background: #E8A87C;
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    text-decoration: none;
    transition: background 0.3s;
}

.wishlist-empty a:hover {
    background: #d6956b;
}

.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.wishlist-card {
    background: white;
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.wishlist-card:hover {
    box-shadow: 0 4px 12px rgba(232, 168, 124, 0.2);
    transform: translateY(-2px);
}

.wishlist-card-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: #f5f5f5;
    position: relative;
}

.wishlist-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.wishlist-card-image .badge-new {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #E8A87C;
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.wishlist-card-content {
    padding: 15px;
}

.wishlist-card-name {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    min-height: 40px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.wishlist-card-price {
    font-size: 16px;
    font-weight: 700;
    color: #E8A87C;
    margin-bottom: 12px;
}

.wishlist-card-price .old-price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
    margin-left: 8px;
}

.wishlist-card-actions {
    display: flex;
    gap: 8px;
}

.wishlist-btn {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.wishlist-btn-cart {
    background: #E8A87C;
    color: white;
}

.wishlist-btn-cart:hover {
    background: #d6956b;
}

.wishlist-btn-remove {
    background: #f5f5f5;
    color: #333;
    border: 1px solid #ddd;
}

.wishlist-btn-remove:hover {
    background: #ff6b6b;
    color: white;
    border-color: #ff6b6b;
}

@media (max-width: 768px) {
    .wishlist-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }

    .wishlist-header h1 {
        font-size: 24px;
    }
}
</style>

<div class="wishlist-container">
    <div class="wishlist-header">
        <h1>
            <i class="bi bi-heart-fill" style="color: #E8A87C;"></i> 
            Danh Sách Yêu Thích
        </h1>
        <div class="wishlist-count">
            <?php if (isset($data['totalItems']) && $data['totalItems'] > 0): ?>
                Bạn có <strong><?= $data['totalItems'] ?></strong> sản phẩm trong danh sách yêu thích
            <?php else: ?>
                Danh sách yêu thích của bạn trống
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($data['wishlist']) && is_array($data['wishlist']) && count($data['wishlist']) > 0): ?>
        <div class="wishlist-grid">
            <?php foreach ($data['wishlist'] as $product): ?>
                <div class="wishlist-card" data-masp="<?= $product['masp'] ?>">
                    <div class="wishlist-card-image">
                        <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($product['hinhanh']) ?>" 
                             alt="<?= htmlspecialchars($product['tensp']) ?>"
                             onerror="this.src='<?= APP_URL ?>/public/images/default.jpg'">
                    </div>
                    <div class="wishlist-card-content">
                        <div class="wishlist-card-name">
                            <?= htmlspecialchars($product['tensp']) ?>
                        </div>
                        <div class="wishlist-card-price">
                            <?php 
                            $price = isset($product['giaXuat']) ? $product['giaXuat'] : 0;
                            $discount = isset($product['khuyenmai']) ? $product['khuyenmai'] : 0;
                            ?>
                            <?= number_format($price, 0, ',', '.') ?> ₫
                            <?php if ($discount > 0): ?>
                                <span class="old-price">
                                    <?= number_format($price + ($price * $discount / 100), 0, ',', '.') ?> ₫
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="wishlist-card-actions">
                            <button class="wishlist-btn wishlist-btn-cart" 
                                    onclick="addToCart('<?= $product['masp'] ?>')">
                                <i class="bi bi-cart-plus"></i> Giỏ
                            </button>
                            <button class="wishlist-btn wishlist-btn-remove" 
                                    onclick="removeFromWishlist('<?= $product['masp'] ?>')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="wishlist-empty">
            <i class="bi bi-heart"></i>
            <p>Danh sách yêu thích của bạn trống</p>
            <a href="<?= APP_URL ?>/">Tiếp tục mua sắm</a>
        </div>
    <?php endif; ?>
</div>

<script>
function removeFromWishlist(masp) {
    if (!confirm('Bạn chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
        return;
    }

    fetch('<?= APP_URL ?>/WishlistController/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'masp=' + masp
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Xóa card từ DOM
            const card = document.querySelector(`[data-masp="${masp}"]`);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => card.remove(), 300);
            }
            
            // Cập nhật số lượng
            updateWishlistCount();
            
            // Hiển thị thông báo
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi: ' + error, 'error');
    });
}

function addToCart(masp) {
    window.location.href = '<?= APP_URL ?>/WishlistController/addToCart/' + masp;
}

function updateWishlistCount() {
    fetch('<?= APP_URL ?>/WishlistController/count', {
        method: 'POST',
    })
    .then(response => response.json())
    .then(data => {
        // Cập nhật counter ở header nếu có
        const wishlistBadge = document.querySelector('.wishlist-count');
        if (wishlistBadge && data.count > 0) {
            wishlistBadge.textContent = `Bạn có ${data.count} sản phẩm trong danh sách yêu thích`;
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
