<?php
class BaseModel extends DB{
       protected $db;

    
    public function __construct() {
        parent::__construct(); // gọi constructor DB để tạo kết nối
        $this->db = $this->Connect(); // đảm bảo $this->db tồn tại
    }

    
       // Danh sách bảng và cột khóa chính tương ứng
    protected    $primaryKeys = [
            'tblsanpham'    => 'masp',
            'tblloaisp'  => 'maLoaiSP',
            'news'       => 'id'
            // thêm các bảng khác nếu cần
        ];
    public function all($table) {
        $sql = "SELECT * FROM $table";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();       
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public  function find($table, $id) {
        // Kiểm tra bảng có trong danh sách không
        if (!array_key_exists($table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        $column = $this->primaryKeys[$table];
        $sql = "SELECT * FROM $table WHERE $column = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //phương thức kiểm tra
    public function check($table, $column, $id) {
        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    // xóa bảng
    public  function delete($table,$id){
        if (!array_key_exists($table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        $column = $this->primaryKeys[$table];
        if($this->check($table, $column, $id)>0){
            $sql="DELETE FROM $table WHERE $column=:id"; 
            $stmt=$this->db->prepare($sql);
            $stmt->bindParam(":id",$id);
            return $stmt->execute();   
        }
        else{
            return false;
        }
        
    }   
    // Thực thi câu lệnh INSERT/UPDATE/DELETE
    public function query($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("BaseModel::query ERROR - SQL: {$sql}, Error: " . $e->getMessage());
            throw $e;
        }
    }

    // Thực thi câu lệnh SELECT
    public function select($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("BaseModel::select ERROR - SQL: {$sql}, Error: " . $e->getMessage());
            throw $e;
        }
    }

    // Lấy ID vừa insert
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    // Insert dữ liệu
    public function create($table, $data) {
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        return $stmt->execute();
    }

    // Update dữ liệu
    public function update($table, $id, $data) {
        if (!array_key_exists($table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        
        $primaryKey = $this->primaryKeys[$table];
        $setClause = '';
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ', ');
        
        $sql = "UPDATE $table SET $setClause WHERE $primaryKey = :id";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(":id", $id);
        
        return $stmt->execute();
    }
}