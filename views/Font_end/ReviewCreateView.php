<?php
/**
 * ReviewCreateView.php - Gửi đánh giá sản phẩm (khách hàng)
 */
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-star"></i> Gửi Đánh Giá
                    </h5>
                </div>

                <div class="card-body">
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/Review/add" id="reviewForm">
                        <!-- Sản phẩm -->
                        <div class="mb-3">
                            <label class="form-label">Sản phẩm:</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($productName); ?>" disabled>
                            <input type="hidden" name="masp" value="<?php echo htmlspecialchars($masp); ?>">
                        </div>

                        <!-- Đơn hàng -->
                        <?php if (isset($orderId)): ?>
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderId); ?>">
                        <?php endif; ?>

                        <!-- Họ tên (nếu chưa đăng nhập) -->
                        <?php if (!isset($_SESSION['user'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Họ tên *</label>
                                <input type="text" class="form-control" name="ten" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        <?php endif; ?>

                        <!-- Đánh giá sao -->
                        <div class="mb-4">
                            <label class="form-label">Đánh giá sao *</label>
                            <div class="rating-selector">
                                <div class="star-rating" id="starRating">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="sosao" id="star<?php echo $i; ?>" value="<?php echo $i; ?>" class="star-input">
                                        <label for="star<?php echo $i; ?>" class="star-label">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                                <span id="starValue" class="ms-3">Chọn đánh giá</span>
                            </div>
                        </div>

                        <!-- Nội dung đánh giá -->
                        <div class="mb-3">
                            <label class="form-label">Nội dung đánh giá *</label>
                            <textarea class="form-control" name="noidung" rows="5" placeholder="Chia sẻ trải nghiệm của bạn với sản phẩm này..." required minlength="10" maxlength="1000"></textarea>
                            <small class="text-muted">Tối thiểu 10 ký tự, tối đa 1000 ký tự</small>
                        </div>

                        <!-- Lưu ý -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Lưu ý:</strong> Đánh giá của bạn sẽ chờ được duyệt trước khi hiển thị công khai.
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Gửi Đánh Giá
                            </button>
                            <a href="<?php echo isset($orderId) ? '/Home/orderDetail/' . htmlspecialchars($orderId) : '/Home/index'; ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lưu ý về nội dung -->
            <div class="card mt-4 bg-light border-0">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-check-circle"></i> Quy tắc gửi đánh giá
                    </h6>
                    <ul class="mb-0 small">
                        <li>Đánh giá phải liên quan đến sản phẩm</li>
                        <li>Tránh bình luận về dịch vụ giao hàng (vui lòng liên hệ với chúng tôi)</li>
                        <li>Không viết quảng cáo hoặc liên kết ngoài</li>
                        <li>Không sử dụng ngôn ngữ xúc phạm hoặc tục tĩu</li>
                        <li>Đánh giá vi phạm quy tắc sẽ bị xóa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-selector {
    display: flex;
    align-items: center;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    font-size: 2rem;
    gap: 10px;
}

.star-input {
    display: none;
}

.star-label {
    cursor: pointer;
    color: #ddd;
    transition: all 0.2s ease;
}

.star-input:hover ~ .star-label,
.star-label:hover {
    color: #ffc107;
}

.star-input:checked ~ .star-label {
    color: #ffc107;
}

.star-rating:has(.star-input:checked) .star-label:not(:has(~ .star-input:checked)) {
    color: #ddd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const starInputs = document.querySelectorAll('input[name="sosao"]');
    const starValue = document.getElementById('starValue');

    // Highlight sao khi hover
    starInputs.forEach(input => {
        input.addEventListener('change', function() {
            starValue.textContent = this.value + ' sao';
        });
    });

    // Validation
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        const noidung = document.querySelector('textarea[name="noidung"]').value.trim();
        const sosao = document.querySelector('input[name="sosao"]:checked');

        if (!sosao) {
            e.preventDefault();
            alert('Vui lòng chọn đánh giá sao');
            return false;
        }

        if (noidung.length < 10) {
            e.preventDefault();
            alert('Nội dung đánh giá phải tối thiểu 10 ký tự');
            return false;
        }
    });
});
</script>
