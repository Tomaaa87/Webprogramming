<?php
require_once 'BaseDao.php';

/**
 * DAO za custom narudzbe
 * dozvoljava dodavanje custom narudzbi za svakog usera
 */
class OrderDao extends BaseDao {
    public function __construct() {
        parent::__construct("orders");
    }

    /** ✅ uzima sve narudzbe*/
    public function getAllOrders() {
        return $this->getAll();
    }
     public function insertOrder($data) {
        return $this->insert([
            "user_id"      => $data["user_id"],
            "status"       => $data["status"],
            "total_amount" => $data["total_amount"]
        ]);
    }

    /** sve narudzbe po korisnikyu */
    public function getByUserId($userId) {
        return $this->query("
            SELECT o.*, c.category_name 
            FROM orders o
            LEFT JOIN categories c ON o.category_id = c.id
            WHERE o.user_id = :uid
            ORDER BY o.order_date DESC
        ", ["uid" => $userId]);
    }

    /** pravi custom order (category, details, price su manualno) */
    public function createCustomOrder($data) {
        
        return $this->insert($data);
    }

    /** status postojeceg ordera */
    public function updateStatus($orderId, $status) {
        return $this->update(["status" => $status], $orderId);
    }
     public function getOrderWithItems($orderId) {
        return $this->query("
            SELECT 
                o.id AS order_id, 
                o.status,
                o.order_date,
                o.total_amount,
                oi.id AS order_item_id,
                oi.quantity,
                oi.price,
                p.name AS product_name,
                p.image_url
            FROM orders o
            LEFT JOIN orderitems oi ON oi.order_id = o.id
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE o.id = :oid
        ", ["oid" => $orderId]);
    }
}
?>

