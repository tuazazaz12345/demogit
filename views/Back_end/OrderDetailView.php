<h2 class="text-center mt-4">Chi tiết đơn hàng #<?= $data['order']['order_code'] ?></h2>

<p><strong>Người nhận:</strong> <?= $data['order']['receiver'] ?></p>
<p><strong>SĐT:</strong> <?= $data['order']['phone'] ?></p>
<p><strong>Địa chỉ:</strong> <?= $data['order']['address'] ?></p>
<p><strong>Trạng thái hiện tại:</strong> <?= $data['order']['trangthai'] ?></p>

<form action="<?= APP_URL ?>/Admin/updateStatus" method="POST" class="mt-3 mb-4">
    <input type="hidden" name="id" value="<?= $data['order']['id'] ?>">
    <input type="hidden" name="admin_token" value="<?= $_SESSION['admin_token'] ?? '' ?>">
    <label for="trangthai">Cập nhật trạng thái:</label>
    <select name="trangthai" class="form-select w-25 d-inline-block">
        <option value="chờ xét duyệt" <?= $data['order']['trangthai'] === 'chờ xét duyệt' ? 'selected' : '' ?>>Chờ xét duyệt</option>
        <option value="đang giao hàng" <?= $data['order']['trangthai'] === 'đang giao hàng' ? 'selected' : '' ?>>Đang giao hàng</option>
        <option value="đã thanh toán" <?= $data['order']['trangthai'] === 'đã thanh toán' ? 'selected' : '' ?>>Đã thanh toán</option>
        <option value="đã hủy" <?= $data['order']['trangthai'] === 'đã hủy' ? 'selected' : '' ?>>Đã hủy</option>
    </select>
    <button type="submit" class="btn btn-success">Cập nhật</button>
</form>

<h4>Danh sách sản phẩm trong đơn:</h4>
<table class="table table-bordered mt-3">
    <thead class="table-light">
        <tr>
            <th>Mã SP</th>
            <th>Tên SP</th>
            <th>Số lượng</th>
            <th>Giá</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data['details'] as $item): ?>
        <tr>
            <td><?= $item['product_id'] ?></td>
            <td><?= $item['product_name'] ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
