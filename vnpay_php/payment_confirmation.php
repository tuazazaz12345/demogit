<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Xác nhận thanh toán</title>
    <link href="/public/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">  
    <script src="/public/js/jquery-1.11.3.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="header clearfix">
            <h3 class="text-muted">Kết quả thanh toán</h3>
        </div>
        <div class="table-responsive">
            <?php
            $vnp_SecureHash = $_GET['vnp_SecureHash'];
            $money = $_GET['vnp_Amount']/100;
            $note = $_GET['vnp_OrderInfo'];
            $vnp_response_code = $_GET['vnp_ResponseCode'];
            $code_vnpay = $_GET['vnp_TransactionNo'];
            $code_bank = $_GET['vnp_BankCode'];
            $time = $_GET['vnp_PayDate'];
            $date_time = substr($time, 0, 4) . '-' . substr($time, 4, 2) . '-' . substr($time, 6, 2) . ' ' . substr($time, 8, 2) . ' ' . substr($time, 10, 2) . ' ' . substr($time, 12, 2);
            $order_id = $_GET['vnp_TxnRef'];
            ?>

            <div class="form-group">
                <label>Số tiền thanh toán:</label>
                <label><?= number_format($money) ?> VND</label>
            </div>  
            <div class="form-group">
                <label>Nội dung thanh toán:</label>
                <label><?= $note ?></label>
            </div>
            <div class="form-group">
                <label>Mã giao dịch VNPAY:</label>
                <label><?= $code_vnpay ?></label>
            </div>
            <div class="form-group">
                <label>Ngân hàng thanh toán:</label>
                <label><?= $code_bank ?></label>
            </div>
            <div class="form-group">
                <label>Thời gian thanh toán:</label>
                <label><?= $date_time ?></label>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-success" onclick="confirmPayment('<?= $order_id ?>')">
                    Xác nhận đã thanh toán thành công
                </button>
            </div>
        </div>
        <script>
        function confirmPayment(orderId) {
            window.location.href = '/CartController/confirmPayment?order_id=' + orderId;
        }
        </script>
    </div>
</body>
</html>