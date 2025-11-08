<?php
require_once 'BaseDao.php';

/**
 * DAO
 * za multi item order
 */
class OrderItemDao extends BaseDao {
    public function __construct() {
        parent::__construct("orderitems");
    }

    /** dodaje product u order*/
    public function addItem($orderId, $productId, $quantity, $price) {
        $data = [
            "order_id" => $orderId,
            "product_id" => $productId,
            "quantity" => $quantity,
            "price" => $price
        ];
        return $this->insert($data);
    }

    /** sve iteme sa opisom za jedan order */
    public function getItemsByOrder($orderId) {
        return $this->query("
            SELECT oi.*, p.name AS product_name
            FROM orderitems oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :oid
        ", ["oid" => $orderId]);
    }

    /** briše iteme po orderu */
    public function deleteByOrder($orderId) {
        return $this->query_execute(
            "DELETE FROM orderitems WHERE order_id = :oid",
            ["oid" => $orderId]
        );
    }
    public function getByOrderId($orderId) {
        return $this->query(
            "SELECT * FROM orderitems WHERE order_id = :oid",
            ["oid" => $orderId]
        );
    }
    public function getAllio() {
        return $this->getAll();
    }
}
?>

