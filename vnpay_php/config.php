<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Load local config (chỉ cập nhật ở đây khi ngrok URL thay đổi)
require_once __DIR__ . '/config.local.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
  
// ⚠️ VNPay Sandbox Test Account (Demo)
// TMN Code: TMNCODE123
$vnp_TmnCode = VNPAY_TMN_CODE;
$vnp_HashSecret = VNPAY_HASH_SECRET;

// VNPay Sandbox URLs
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = VNPAY_RETURN_URL;
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
//Config input format
//Expire
$startTime = date("YmdHis");
$expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
