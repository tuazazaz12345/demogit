<?php
$products = $data['products'] ?? [];
?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2><i class="bi bi-plus-circle"></i> Thêm Khuyến Mại</h2>
        </div>
    </div>

    <form method="POST" action="<?php echo APP_URL; ?>/PromotionController/store" class="row g-3">
        <div class="col-md-6">
            <label for="name" class="form-label">Tên Khuyến Mại <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" required 
                   placeholder="Nhập tên khuyến mại">
        </div>

        <div class="col-md-6">
            <label for="discount_percent" class="form-label">Chiết Khấu (%) <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="discount_percent" name="discount_percent" 
                   required min="0" max="100" step="0.01"
                   placeholder="Nhập phần trăm chiết khấu">
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="description" name="description" rows="3"
                      placeholder="Nhập mô tả chi tiết về khuyến mại"></textarea>
        </div>

        <div class="col-md-6">
            <label for="start_date" class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
        </div>

        <div class="col-md-6">
            <label for="end_date" class="form-label">Ngày Kết Thúc <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
        </div>

        <div class="col-12">
            <label class="form-label">Áp Dụng Cho Sản Phẩm</label>
            <div class="card">
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="products[]" 
                                       value="<?php echo $product['masp']; ?>" 
                                       id="product_<?php echo $product['masp']; ?>">
                                <label class="form-check-label" for="product_<?php echo $product['masp']; ?>">
                                    <?php echo htmlspecialchars($product['tensp']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Không có sản phẩm nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                <label class="form-check-label" for="status">
                    Kích hoạt khuyến mại
                </label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Thêm Khuyến Mại
            </button>
            <a href="<?php echo APP_URL; ?>/PromotionController/index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </form>
</div>
