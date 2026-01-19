<?php
require_once __DIR__ . "/BaseModel.php";

class NewsModel extends BaseModel {
    const TABLE = 'news';

    public function getAll()
    {
        return $this->all(self::TABLE);
    }

    public function findById($id)
    {
        return $this->find(self::TABLE, $id);
    }

    public function store($data)
    {
        return $this->create(self::TABLE, $data);
    }

    public function updateNews($id, $data)
    {
        return $this->update(self::TABLE, $id, $data);
    }

    public function deleteNews($id)
    {
        return $this->delete(self::TABLE, $id);
    }
}