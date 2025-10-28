<?php
require_once 'BaseDao.php';

class OrderItemDao extends BaseDao {
    public function __construct() {
        parent::__construct("orderItems");
    }

    public function getByOrderId($order_id) {
        $stmt = $this->connection->prepare("SELECT * FROM orderItems WHERE order_id = :oid");
        $stmt->bindParam(':oid', $order_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
