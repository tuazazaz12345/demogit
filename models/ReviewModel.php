<?php
require_once 'BaseModel.php';

class ReviewModel extends BaseModel {
    protected $table = 'tblreview';

    // Lấy danh sách đánh giá đã duyệt của 1 sản phẩm
    public function getReviewsByProduct($masp) {
        $sql = "SELECT r.*, u.fullname, o.order_code 
                FROM {$this->table} r 
                LEFT JOIN users u ON r.email = u.email 
                LEFT JOIN orders o ON r.order_id = o.id 
                WHERE r.masp = :masp AND (r.trangthai = 'đã duyệt' OR r.moderation_status = 'approved')
                ORDER BY r.ngaygui DESC";
        return $this->select($sql, ['masp' => $masp]);
    }

    // Tính điểm trung bình
    public function getAverageRating($masp) {
        $sql = "SELECT ROUND(AVG(sosao),1) AS trungbinh 
                FROM {$this->table} 
                WHERE masp = :masp AND (trangthai = 'đã duyệt' OR moderation_status = 'approved')";
        $result = $this->select($sql, ['masp' => $masp]);
        return $result ? $result[0]['trungbinh'] : 0;
    }

    // Thêm đánh giá mới
    public function addReview($data) {
        $sql = "INSERT INTO {$this->table} (masp, ten, email, noidung, sosao, order_id, trangthai, moderation_status)
                VALUES (:masp, :ten, :email, :noidung, :sosao, :order_id, 'chờ duyệt', 'pending')";
        
        try {
            $stmt = $this->query($sql, $data);
            $insertId = $this->getLastInsertId();
            error_log("ReviewModel::addReview SUCCESS - masp: {$data['masp']}, insertId: {$insertId}");
            return $insertId;
        } catch (Exception $e) {
            error_log("ReviewModel::addReview FAILED - Error: " . $e->getMessage() . ", Data: " . json_encode($data));
            return false;
        }
    }

    // Lấy toàn bộ đánh giá (cho admin)
    public function getAllReviews() {
        $sql = "SELECT r.*, p.tensp 
                FROM {$this->table} r 
                JOIN tblsanpham p ON r.masp = p.masp 
                ORDER BY r.ngaygui DESC";
        return $this->select($sql);
    }

    // Cập nhật trạng thái (duyệt / ẩn)
    public function updateStatus($id, $trangthai) {
        $sql = "UPDATE {$this->table} SET trangthai = :trangthai WHERE id = :id";
        return $this->query($sql, ['trangthai' => $trangthai, 'id' => $id]);
    }

    // Xóa đánh giá
    public function deleteReview($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->query($sql, ['id' => $id]);
    }

    // ✅ Xóa tất cả đánh giá của 1 sản phẩm (khi xóa sản phẩm)
    public function deleteByProductId($masp) {
        $sql = "DELETE FROM {$this->table} WHERE masp = :masp";
        return $this->query($sql, ['masp' => $masp]);
    }

    // Lấy đánh giá theo đơn hàng
    public function getReviewsByOrder($orderId) {
        $sql = "SELECT r.*, p.tensp, p.masp 
                FROM {$this->table} r 
                JOIN tblsanpham p ON r.masp = p.masp 
                JOIN order_details od ON r.order_id = od.order_id AND r.masp = od.product_id
                WHERE r.order_id = :orderId AND (r.trangthai = 'đã duyệt' OR r.moderation_status = 'approved')";
        return $this->select($sql, ['orderId' => $orderId]);
    }

    // Kiểm tra xem sản phẩm trong đơn hàng đã được đánh giá chưa
    public function checkReviewExists($orderId, $masp) {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} 
                WHERE order_id = :orderId AND masp = :masp";
        try {
            $result = $this->select($sql, [
                'orderId' => $orderId,
                'masp' => $masp
            ]);
            return $result && count($result) > 0 && $result[0]['count'] > 0;
        } catch (Exception $e) {
            error_log("ReviewModel::checkReviewExists ERROR: " . $e->getMessage());
            return false;
        }
    }
    
    // =============== MODERATION METHODS ===============
    
    /**
     * Lấy danh sách review cần kiểm duyệt
     */
    public function getPendingReviews() {
        $sql = "SELECT r.*, COALESCE(p.tensp, 'Sản phẩm đã xóa') as tensp 
                FROM {$this->table} r 
                LEFT JOIN tblsanpham p ON r.masp = p.masp 
                WHERE r.moderation_status = 'pending' OR r.trangthai = 'chờ duyệt'
                ORDER BY r.ngaygui DESC";
        return $this->select($sql);
    }
    
    /**
     * Lấy review spam/rejected
     */
    public function getRejectedReviews() {
        $sql = "SELECT r.*, p.tensp 
                FROM {$this->table} r 
                JOIN tblsanpham p ON r.masp = p.masp 
                WHERE r.moderation_status IN ('spam', 'rejected')
                ORDER BY r.moderation_date DESC";
        return $this->select($sql);
    }
    
    /**
     * Lấy review đã duyệt
     */
    public function getApprovedReviews() {
        $sql = "SELECT r.*, p.tensp 
                FROM {$this->table} r 
                JOIN tblsanpham p ON r.masp = p.masp 
                WHERE r.moderation_status = 'approved' OR r.trangthai = 'đã duyệt'
                ORDER BY r.ngaygui DESC";
        return $this->select($sql);
    }
    
    /**
     * Lấy thống kê kiểm duyệt
     */
    public function getModerationStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN moderation_status = 'pending' OR trangthai = 'chờ duyệt' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN moderation_status = 'approved' OR trangthai = 'đã duyệt' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN moderation_status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN moderation_status = 'spam' THEN 1 ELSE 0 END) as spam
                FROM {$this->table}";
        $result = $this->select($sql);
        return $result ? $result[0] : null;
    }
    
    /**
     * Cập nhật trạng thái kiểm duyệt
     */
    public function updateModerationStatus($reviewId, $status, $adminId = null, $reason = '', $notes = '') {
        $trangthai = 'chờ duyệt'; // Default
        
        if ($status === 'approved') {
            $trangthai = 'đã duyệt';
        } elseif ($status === 'rejected') {
            $trangthai = 'bị từ chối';
        } elseif ($status === 'spam') {
            $trangthai = 'spam';
        }
        
        $sql = "UPDATE {$this->table} 
                SET moderation_status = :status, 
                    trangthai = :trangthai,
                    moderated_by = :adminId,
                    moderation_date = NOW(),
                    ly_do_tu_choi = :reason,
                    moderation_notes = :notes
                WHERE id = :id";
        
        return $this->query($sql, [
            'status' => $status,
            'trangthai' => $trangthai,
            'adminId' => $adminId,
            'reason' => $reason,
            'notes' => $notes,
            'id' => $reviewId
        ]);
    }
    
    /**
     * Ghi nhận spam score từ tự động kiểm duyệt
     */
    public function recordSpamAnalysis($reviewId, $spamScore, $prohibitedWords = 0) {
        $sql = "UPDATE {$this->table} 
                SET flagged_as_spam = :spamScore,
                    contains_prohibited_words = :prohibitedWords
                WHERE id = :id";
        
        return $this->query($sql, [
            'spamScore' => $spamScore,
            'prohibitedWords' => $prohibitedWords,
            'id' => $reviewId
        ]);
    }
    
    /**
     * Lấy chi tiết review để kiểm duyệt
     */
    public function getReviewDetail($reviewId) {
        $sql = "SELECT r.*, p.tensp, p.masp, u.fullname as user_fullname
                FROM {$this->table} r 
                LEFT JOIN tblsanpham p ON r.masp = p.masp 
                LEFT JOIN users u ON r.email = u.email
                WHERE r.id = :id";
        $result = $this->select($sql, ['id' => $reviewId]);
        return $result ? $result[0] : null;
    }
    
    /**
     * Bulk update status
     */
    public function bulkUpdateStatus($reviewIds, $status, $adminId = null) {
        if (empty($reviewIds)) {
            return false;
        }
        
        $trangthai = 'chờ duyệt'; // Default
        if ($status === 'approved') {
            $trangthai = 'đã duyệt';
        } elseif ($status === 'rejected') {
            $trangthai = 'bị từ chối';
        } elseif ($status === 'spam') {
            $trangthai = 'spam';
        }
        
        $placeholders = implode(',', array_fill(0, count($reviewIds), '?'));
        $sql = "UPDATE {$this->table} 
                SET moderation_status = ?,
                    trangthai = ?,
                    moderated_by = ?,
                    moderation_date = NOW()
                WHERE id IN ($placeholders)";
        
        $params = array_merge([$status, $trangthai, $adminId], $reviewIds);
        return $this->query($sql, $params);
    }
}
