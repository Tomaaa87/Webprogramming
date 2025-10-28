<?php
require_once 'BaseDao.php';

class ReviewDao extends BaseDao {
    public function __construct() {
        parent::__construct("reviews");
    }

    public function getByProductId($product_id) {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE product_id = :pid");
        $stmt->bindParam(':pid', $product_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
