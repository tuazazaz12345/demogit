<?php

class PolicyController extends Controller {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Hiển thị trang chính sách bảo hành & đổi trả
     */
    public function warranty() {
        $this->view('homePage', [
            'page' => 'PolicyWarrantyView',
            'title' => 'Chính Sách Bảo Hành & Đổi Trả'
        ]);
    }
    
    /**
     * Hiển thị trang chính sách thanh toán
     */
    public function payment() {
        $this->view('homePage', [
            'page' => 'PolicyPaymentView',
            'title' => 'Chính Sách Thanh Toán'
        ]);
    }
    
    /**
     * Hiển thị trang chính sách giao hàng
     */
    public function shipping() {
        $this->view('homePage', [
            'page' => 'PolicyShippingView',
            'title' => 'Chính Sách Giao Hàng'
        ]);
    }
    
    /**
     * Hiển thị trang điều khoản dịch vụ
     */
    public function terms() {
        $this->view('homePage', [
            'page' => 'PolicyTermsView',
            'title' => 'Điều Khoản Dịch Vụ'
        ]);
    }
    
    /**
     * Hiển thị trang chính sách bảo mật
     */
    public function privacy() {
        $this->view('homePage', [
            'page' => 'PolicyPrivacyView',
            'title' => 'Chính Sách Bảo Mật'
        ]);
    }
}
