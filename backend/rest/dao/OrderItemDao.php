<?php
require_once 'BaseDao.php';

/**
 * DAO
 * za multi item order
 */
class OrderItemDao extends BaseDao {
    public function __construct() {
        parent::__construct("order_items");
    }

    /** dodaje product u order*/
    public function addItem($orderId, $productId, $quantity, $price) {
    return $this->add([
        "order_id"   => $orderId,
        "product_id" => $productId,
        "quantity"   => $quantity,
        "price"      => $price
    ]);
    }
    /** sve iteme sa opisom za jedan order */
    public function getItemsByOrder($orderId) {
        return $this->query("
            SELECT oi.*, p.name AS product_name
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :oid
        ", ["oid" => $orderId]);
    }

    /** briše iteme po orderu */
    public function deleteByOrder($orderId) {
        return $this->execute_query(
            "DELETE FROM order_items WHERE order_id = :oid",
            ["oid" => $orderId]
        );
    }
    public function getByOrderId($orderId) {
        return $this->query(
            "SELECT * FROM order_items WHERE order_id = :oid",
            ["oid" => $orderId]
        );
    }
    public function getAllio() {
        return $this->getAll();
    }

    public function updateQuantity($id, $quantity) {
        return $this->update(["quantity" => $quantity], $id);
    }

    public function deleteItem($id) {
        return $this->delete($id);
    }

    public function getTotalByOrder($orderId) {
        return $this->query_unique("
            SELECT SUM(quantity * price) as total
            FROM order_items
            WHERE order_id = :oid
        ", ["oid" => $orderId]);
    }

    public function getQuantityByProduct($productId) {
        return $this->query_unique("
            SELECT SUM(quantity) as total_quantity
            FROM order_items
            WHERE product_id = :pid
        ", ["pid" => $productId]);
    }
}
?>

