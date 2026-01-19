<?php
require_once "BaseModel.php";
class AdProductTypeModel extends BaseModel{
    private $table="tblloaisp";
    public function insert($maLoaiSP, $tenLoaiSP, $moTaLoaiSP) {
        // Kiểm tra bảng có trong danh sách không
        if (!array_key_exists($this->table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        // Kiểm tra xem mã loại sản phẩm đã tồn tại chưa
        $column = $this->primaryKeys[$this->table];
        if($this->check($this->table, $column, $maLoaiSP)>0){
            echo "Mã loại sản phẩm đã tồn tại. Vui lòng chọn mã khác.";
            return;
        }
        else{
            // Chuẩn bị câu lệnh INSERT
            $sql = "INSERT INTO tblloaisp (maLoaiSP, tenLoaiSP, moTaLoaiSP) 
                    VALUES (:maLoaiSP, :tenLoaiSP, :moTaLoaiSP)";
            try {
                $stmt = $this->db->prepare($sql);
                // Gán giá trị cho các tham số
                $stmt->bindParam(':maLoaiSP', $maLoaiSP);
                $stmt->bindParam(':tenLoaiSP', $tenLoaiSP);
                $stmt->bindParam(':moTaLoaiSP', $moTaLoaiSP);
                // Thực thi câu lệnh
                $stmt->execute();
                echo "Thêm loại sản phẩm thành công.";
            } catch (PDOException $e) {
                echo "Thất bại" . $e->getMessage();
            } 
        }    
    }
    
    public function update($maLoaiSP, $tenLoaiSP, $moTaLoaiSP) {
        // Chuẩn bị câu lệnh UPDATE
        $sql = "UPDATE tblloaisp SET 
                tenLoaiSP = :tenLoaiSP, 
                moTaLoaiSP = :moTaLoaiSP
                WHERE maLoaiSP = :maLoaiSP";
        try {
            $stmt = $this->db->prepare($sql); 
            // Gán giá trị cho các tham số
            $stmt->bindParam(':maLoaiSP', $maLoaiSP);
            $stmt->bindParam(':tenLoaiSP', $tenLoaiSP);
            $stmt->bindParam(':moTaLoaiSP', $moTaLoaiSP);

            // Thực thi câu lệnh
            $stmt->execute();
            echo "Cập nhật loại sản phẩm thành công.";
        } catch (PDOException $e) {
            echo "Cập nhật không thành công: " . $e->getMessage();
        }
    }
    
}
