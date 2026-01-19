<?php
require_once __DIR__ . "/BaseModel.php";

class SearchModel extends BaseModel {
    public function searchProducts($keyword) {
        try {
            $keyword = trim($keyword);
            if ($keyword === "") return [];

            // Dùng wildcard
            $searchTerm = "%" . $keyword . "%";

            // Debug: log từ khóa
            error_log("[SearchModel] searchProducts keyword: " . $keyword);

            // Query: tìm theo tensp hoặc masp
            $sql = "SELECT * FROM tblsanpham
                    WHERE tensp LIKE :keyword
                       OR masp LIKE :keyword";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':keyword', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            error_log("[SearchModel] returned rows: " . count($results));
            if (count($results) > 0) {
                error_log("[SearchModel] first row: " . print_r($results[0], true));
            }

            return $results;
        } catch (PDOException $e) {
            error_log("[SearchModel] PDOException: " . $e->getMessage());
            return [];
        }
    }
}
