<?php
class DB {
    private $host='localhost';
    private $user='root';
    private $pass='';
    private $dbname='website (6)';
    //viết phương thức kết nối csdl
    protected $db;
    public function __construct(){
        $this->db=$this->Connect();
    }   
    public function Connect(){
        $dsn='mysql:host='.$this->host.';dbname='.$this->dbname;
        $pdo=new PDO($dsn,$this->user,$this->pass);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        return $pdo;
    }
}