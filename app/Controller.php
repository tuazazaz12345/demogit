<?php
class Controller {
    protected $db;

    public function __construct() {
        $this->db = $this->getDB();
    }

    public function getDB() {
        require_once __DIR__ . "/DB.php";
        return (new DB())->Connect();
    }

    public function model($model){
       require_once "./models/".$model.".php";
       return new $model;
    }
    public function view($view,$data=array()){
        extract($data);
        require_once "./views/".$view.".php";
    }
}