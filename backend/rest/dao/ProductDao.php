<?php
require_once 'BaseDao.php';

class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct("products");
    }

    public function getByCategory($category_id) {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE category_id = :cid");
        $stmt->bindParam(':cid', $category_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>