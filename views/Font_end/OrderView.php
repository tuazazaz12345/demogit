<?php if (!empty($data['success'])): ?>
    <div class="alert alert-success text-center mt-3">
        <?= htmlspecialchars($data['success']) ?>
    </div>
<?php endif; ?>
<form action="<?= APP_URL ?>/Home/update" method="post">
<div class="container my-5">
    <h2 class="mb-4">🛒 Giỏ Hàng Của Bạn</h2>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th>Giá bán</th>
                <th>Khuyến Mãi</th>
                <th>Số lượng</th>
                <th>Thành Tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
            <?php 
                $i = 0;
                if (is_array($data["listProductOrder"]) && !empty($data["listProductOrder"])) {
                    foreach ($data["listProductOrder"] as $masp => $v): 
                    // Kiểm tra $v có phải array không, nếu không thì skip
                    if (!is_array($v)) continue;
                    $i++;
                    // Extract với default values
                    $hinhanh = $v['hinhanh'] ?? '';
                    $masp = $v['masp'] ?? $masp;
                    $tensp = $v['tensp'] ?? '';
                    $giaxuat = $v['giaxuat'] ?? 0;
                    $khuyenmai = $v['khuyenmai'] ?? 0;
                    $qty = $v['qty'] ?? 1;
                    $from_promotion = $v['from_promotion'] ?? false;
                    $promotional_price = $v['promotional_price'] ?? null;
            ?>
            <tr>
                <td><?= $i?></td>
                <td>
                    <img src="<?php echo APP_URL;?>/public/images/<?= htmlspecialchars($hinhanh) ?>" 
                            class="card-img-top"  style="width: 100%; height: 9rem; object-fit: contain;" >
                            <br>
                    <?= htmlspecialchars($masp) ?>
                    <br>
                    <?= htmlspecialchars($tensp) ?>
                 </td>  
                <td><?= number_format($giaxuat, 0, ',', '.') ?> ₫</td>
                <td>
                    <?php 
                    // ✅ Tính % giảm thực tế
                    if ($from_promotion && isset($promotional_price)) {
                        // Từ hệ thống KM: tính % từ giá gốc & giá KM
                        $percent_reduction = (($giaxuat - $promotional_price) / $giaxuat) * 100;
                        echo number_format($percent_reduction, 0) . '%';
                    } else {
                        // Khuyến mãi cơ bản
                        echo htmlspecialchars($khuyenmai) . '%';
                    }
                    ?>
                </td>
                <td>
                  <input type="number" name="qty[<?= htmlspecialchars($masp) ?>]" value="<?= $qty ?>" min="1"
                          class="form-control form-control-sm" style="width: 80px;">
                </td>
                <td><?php
                    // ✅ NẾU TỪ TRANG KHUYẾN MẠI: Dùng giá khuyến mãi đã lưu
                    if ($from_promotion && isset($promotional_price)) {
                        $gia = $promotional_price;
                    } else {
                        // ✅ BÌNH THƯỜNG: Tính từ khuyến mại %
                        if($khuyenmai > 0){
                            $gia = $giaxuat - ($giaxuat * $khuyenmai / 100);
                        }
                        else{
                            $gia = $giaxuat;
                        }
                    }
                    $thanhtien = $gia * $qty;
                    echo number_format($thanhtien, 0, ',', '.');
                    ?> ₫
                </td>
                <td>
                    <a href="<?= APP_URL ?>/Home/delete/<?= htmlspecialchars($masp) ?>" 
                        class="btn btn-danger btn-sm" 
                        onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?');">
                        🗑️ Xoá
                    </a>
                    
                </td>
            </tr>
            <?php endforeach; 
                } else {
            ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Giỏ hàng trống. <a href="<?= APP_URL ?>/Home/">Tiếp tục mua sắm</a>
                    </td>
                </tr>
            <?php } ?>
           
    </table>
    <div class="text-end">
        <button type="submit" class="btn btn-primary">🔄 Cập nhật giỏ hàng</button>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="<?php echo APP_URL . '/Home/checkoutInfo'; ?>" class="btn btn-success ms-2">🛒 Đặt hàng</a>
        <?php else: ?>
            <a href="<?php echo APP_URL . '/AuthController/showLogin'; ?>" class="btn btn-success ms-2" onclick="alert('Vui lòng đăng nhập để đặt hàng!');">🛒 Đặt hàng</a>
        <?php endif; ?>
    </div>
</div>
</form>