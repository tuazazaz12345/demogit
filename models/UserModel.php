 <?php
class UserModel extends DB {
    // Tìm user theo email
 
    private $table = "tbluser";
    public $email;
    public $password;
    public $fullname;
    public $token;
    public function create() {
        $query = "INSERT INTO {$this->table} (fullname, email, password, verification_token) VALUES (:fullname, :email, :password, :token)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":fullname", $this->fullname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":token", $this->token);
        return $stmt->execute();
    }

    public function verify($token) {
        $query = "SELECT * FROM {$this->table} WHERE verification_token = :token AND is_verified = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt;
    }

    public function setVerified($token) {
        $query = "UPDATE {$this->table} SET is_verified = 1, verification_token = NULL WHERE verification_token = :token";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":token", $token);
        return $stmt->execute();
    }

        // Kiểm tra email đã tồn tại chưa
    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
       public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt;
    }

       // Đặt lại mật khẩu mới cho user
    public function updatePassword($email, $newPasswordHash) {
        $query = "UPDATE {$this->table} SET password = :password WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":password", $newPasswordHash);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }

    public function updateProfile($email, $fullname, $phone, $address) {
        $query = "UPDATE {$this->table} SET fullname = :fullname, phone = :phone, address = :address WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":fullname", $fullname);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }
}
