<?php
?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2><i class="bi bi-plus-circle"></i> Thêm Mã Giảm Giá</h2>
        </div>
    </div>

    <form method="POST" action="<?php echo APP_URL; ?>/DiscountCodeController/store" class="row g-3">
        <div class="col-md-6">
            <label for="code" class="form-label">Mã Giảm Giá <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="code" name="code" required 
                   placeholder="VD: SUMMER2024" style="text-transform: uppercase;">
        </div>

        <div class="col-md-6">
            <label for="discount_type" class="form-label">Loại Giảm Giá <span class="text-danger">*</span></label>
            <select class="form-select" id="discount_type" name="discount_type" required>
                <option value="percentage">Phần Trăm (%)</option>
                <option value="fixed">Cố Định (₫)</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="discount_value" class="form-label">Giá Trị Giảm <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="discount_value" name="discount_value" 
                   required min="0" step="0.01"
                   placeholder="Nhập giá trị giảm">
        </div>

        <div class="col-md-6">
            <label for="max_discount" class="form-label">Giảm Tối Đa (₫)</label>
            <input type="number" class="form-control" id="max_discount" name="max_discount" 
                   min="0" step="0.01"
                   placeholder="Để trống nếu không giới hạn">
        </div>

        <div class="col-md-6">
            <label for="min_order_value" class="form-label">Đơn Hàng Tối Thiểu (₫)</label>
            <input type="number" class="form-control" id="min_order_value" name="min_order_value" 
                   min="0" step="0.01" value="0"
                   placeholder="Giá trị đơn hàng tối thiểu để áp dụng">
        </div>

        <div class="col-md-6">
            <label for="usage_limit" class="form-label">Giới Hạn Lượt Sử Dụng</label>
            <input type="number" class="form-control" id="usage_limit" name="usage_limit" 
                   min="1" placeholder="Để trống nếu không giới hạn">
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="description" name="description" rows="3"
                      placeholder="Nhập mô tả chi tiết về mã giảm giá"></textarea>
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
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                <label class="form-check-label" for="status">
                    Kích hoạt mã giảm giá
                </label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Thêm Mã Giảm Giá
            </button>
            <a href="<?php echo APP_URL; ?>/DiscountCodeController/index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </form>
</div>
