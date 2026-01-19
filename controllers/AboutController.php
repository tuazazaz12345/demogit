<?php

class AboutController extends Controller {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Hiển thị trang giới thiệu
     */
    public function index() {
        $this->view('homePage', [
            'page' => 'AboutView',
            'title' => 'Giới Thiệu'
        ]);
    }
}
