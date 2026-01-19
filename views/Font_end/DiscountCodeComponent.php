<!-- Form áp dụng mã giảm giá trong giỏ hàng -->
<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-percent"></i> Áp Dụng Mã Giảm Giá</h5>
        <form id="discountCodeForm" class="input-group">
            <input type="text" class="form-control" id="discountCode" placeholder="Nhập mã giảm giá" 
                   style="text-transform: uppercase;">
            <button type="button" class="btn btn-primary" onclick="applyDiscountCode()">
                <i class="bi bi-check-circle"></i> Áp Dụng
            </button>
        </form>
        <div id="discountMessage" class="mt-2"></div>
        <div id="discountSummary" class="mt-3" style="display: none;">
            <div class="row">
                <div class="col-6">Mã được áp dụng:</div>
                <div class="col-6 text-end"><strong id="appliedCode"></strong></div>
            </div>
            <div class="row mt-2">
                <div class="col-6">Tiền giảm:</div>
                <div class="col-6 text-end text-success"><strong id="discountAmount"></strong></div>
            </div>
            <button type="button" class="btn btn-sm btn-warning mt-2" onclick="removeDiscountCode()">
                <i class="bi bi-x-circle"></i> Hủy Mã
            </button>
        </div>
    </div>
</div>

<script>
function applyDiscountCode() {
    const code = document.getElementById('discountCode').value.trim();
    const messageDiv = document.getElementById('discountMessage');
    
    if (!code) {
        showMessage('Vui lòng nhập mã giảm giá', 'warning');
        return;
    }

    // Lấy tổng tiền từ form hoặc tính toán
    let totalAmount = 0;
    const quantities = document.querySelectorAll('input[name="qty[]"]');
    const prices = document.querySelectorAll('input[name="price[]"]');
    
    quantities.forEach((qty, index) => {
        totalAmount += parseInt(qty.value) * parseFloat(prices[index].value);
    });

    // Gọi API kiểm tra mã
    fetch('<?php echo APP_URL; ?>/DiscountCodeController/verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'code=' + encodeURIComponent(code) + '&total=' + totalAmount
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            document.getElementById('appliedCode').textContent = code;
            document.getElementById('discountAmount').textContent = 
                formatCurrency(data.discount) + ' ₫';
            document.getElementById('discountSummary').style.display = 'block';
            showMessage('✓ Mã giảm giá được áp dụng thành công!', 'success');
            
            // Lưu mã vào session hoặc form
            document.getElementById('discountCode').dataset.applied = code;
            document.getElementById('discountCode').dataset.discount = data.discount;
            document.getElementById('discountCode').dataset.finalTotal = data.final_total;
        } else {
            showMessage(data.message || 'Mã giảm giá không hợp lệ', 'danger');
            document.getElementById('discountSummary').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Lỗi khi kiểm tra mã giảm giá', 'danger');
    });
}

function removeDiscountCode() {
    document.getElementById('discountCode').value = '';
    document.getElementById('discountCode').dataset.applied = '';
    document.getElementById('discountMessage').innerHTML = '';
    document.getElementById('discountSummary').style.display = 'none';
}

function showMessage(message, type) {
    const messageDiv = document.getElementById('discountMessage');
    messageDiv.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}

function formatCurrency(value) {
    return new Intl.NumberFormat('vi-VN').format(value);
}
</script>

<style>
.input-group {
    border-radius: 0.375rem;
    overflow: hidden;
}

#discountMessage {
    margin-top: 0.5rem;
}

#discountSummary {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    border-left: 4px solid #28a745;
}
</style>
