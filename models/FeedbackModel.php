<?php
require_once __DIR__ . "/../app/DB.php";
require_once __DIR__ . "/BaseModel.php";

class FeedbackModel extends BaseModel {
    const TABLE = "feedback";

    public function __construct() {
        parent::__construct();
        // Thêm bảng feedback vào danh sách primaryKeys của BaseModel
        $this->primaryKeys['feedback'] = 'id';
    }

    public function addFeedback($data) {
        $insertData = [
            'user_email' => $data['user_email'],
            'fullname' => $data['fullname'],
            'content' => $data['content'],
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->create(self::TABLE, $insertData);
    }

    public function getAllFeedback() {
        return $this->select("SELECT * FROM " . self::TABLE . " ORDER BY created_at DESC");
    }

    public function updateStatus($id, $status) {
        return $this->update(self::TABLE, $id, ['status' => $status]);
    }

    public function deleteFeedback($id) {
        return $this->delete(self::TABLE, $id);
    }

    // ✅ Xóa tất cả feedback của 1 sản phẩm (khi xóa sản phẩm)
    public function deleteByProductId($masp) {
        // Nếu bảng feedback có liên kết với sản phẩm
        $sql = "DELETE FROM " . self::TABLE . " WHERE product_id = ?";
        try {
            $this->db->prepare($sql)->execute([$masp]);
            return true;
        } catch (Exception $e) {
            // Có thể feedback không liên kết với product_id
            error_log("FeedbackModel::deleteByProductId - " . $e->getMessage());
            return false;
        }
    }
}
?>