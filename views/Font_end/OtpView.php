<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực OTP</title>
    <link rel="stylesheet" href="/public/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Nhập mã OTP đã gửi tới email</h2>
    <form action="<?php echo APP_URL;?>/AuthController/<?php echo isset($data['isAdmin']) ? 'verifyAdminOtp' : 'verifyOtp'; ?>" method="POST"> 
        <div class="mb-3">
            <label for="otp" class="form-label">Mã OTP</label>
            <input type="text" class="form-control" id="otp" name="otp" required>
        </div>
        <button type="submit" class="btn btn-success">Xác thực</button>
    </form>
</div>
</body>
</html>
