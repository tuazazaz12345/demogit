<?php
require_once 'BaseModel.php';

class ChatboxModel extends BaseModel {
    protected $table = 'chatbox_messages';
    
    /**
     * Lưu tin nhắn mới
     */
    public function sendMessage($email, $name, $message) {
        try {
            $sql = "INSERT INTO {$this->table} (user_email, user_name, message, status)
                    VALUES (?, ?, ?, 'pending')";
            $this->query($sql, [$email, $name, $message]);
            return ['success' => true, 'message' => 'Tin nhắn đã được gửi. Chúng tôi sẽ phản hồi sớm!'];
        } catch (Exception $e) {
            error_log("Lỗi gửi tin nhắn: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi gửi tin nhắn'];
        }
    }
    
    /**
     * Lấy tin nhắn của user
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_email = ?
                ORDER BY created_at DESC";
        return $this->select($sql, [$email]);
    }
    
    /**
     * Lấy tất cả tin nhắn (cho admin)
     */
    public function getAllMessages($status = null) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        return $this->select($sql, $params);
    }
    
    /**
     * Cập nhật phản hồi (admin)
     */
    public function respondMessage($id, $response) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET response_text = ?, status = 'responded', responded_at = NOW()
                    WHERE id = ?";
            $this->query($sql, [$response, $id]);
            return ['success' => true, 'message' => 'Phản hồi đã được gửi'];
        } catch (Exception $e) {
            error_log("Lỗi cập nhật phản hồi: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi cập nhật'];
        }
    }
    
    /**
     * Đóng tin nhắn
     */
    public function closeMessage($id) {
        try {
            $sql = "UPDATE {$this->table} SET status = 'closed' WHERE id = ?";
            $this->query($sql, [$id]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false];
        }
    }
    
    /**
     * Lấy chi tiết 1 tin nhắn
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->select($sql, [$id]);
        return $result ? $result[0] : null;
    }
}
