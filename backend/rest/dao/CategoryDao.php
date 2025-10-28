<?php
require_once 'BaseDao.php';

class CategoryDao extends BaseDao {
    public function __construct() {
        parent::__construct("categories");
    }

    public function searchByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM categories WHERE category_name LIKE :name");
        $like = "%$name%";
        $stmt->bindParam(':name', $like);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
