<?php
require_once 'Basedao.php';

/**
 * DAO za custom narudzbe
 * dozvoljava dodavanje custom narudzbi za svakog usera
 */
class OrderDao extends BaseDao {
    public function __construct() {
        parent::__construct("orders");
    }

    /** uzima sve narudzbe*/
    public function getAllOrders() {
        return $this->getAll();
    }
    public function getOrderById($id) {
        return $this->getById($id);
    }
     public function insertOrder($data) {
        return $this->add([
            "user_id"      => $data["user_id"],
            "status"       => $data["status"],
            "total_amount" => $data["total_amount"]
        ]);
    }

    /** sve narudzbe po korisnikyu */
    public function getByUserId($userId) {
        return $this->query("
            SELECT o.* 
            FROM orders o
            WHERE o.user_id = :uid
            ORDER BY o.created_at DESC
        ", ["uid" => $userId]);
    }

    /** pravi custom order (category, details, price su manualno) */
    public function createCustomOrder($data) {
        
        return $this->add($data);
    }

    /** status postojeceg ordera */
    public function updateStatus($orderId, $status) {
        return $this->update(["status" => $status], $orderId);
    }
    public function getByStatus($status) {
        return $this->query("SELECT * FROM orders WHERE status = :status ORDER BY created_at DESC", ["status" => $status]);
    }
    public function getRecentOrders($limit = 10) {
        $limit = (int)$limit;
        return $this->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT $limit", []);
    }
     public function getOrderWithItems($orderId) {
        return $this->query("
            SELECT 
                o.id AS order_id, 
                o.status,
                o.created_at,
                o.total_amount,
                oi.id AS order_item_id,
                oi.quantity,
                oi.price,
                p.name AS product_name
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE o.id = :oid
        ", ["oid" => $orderId]);
    }
    
    /** Delete an order by ID */
    public function deleteOrder($id) {
        return $this->delete($id);
    }
}
?>

