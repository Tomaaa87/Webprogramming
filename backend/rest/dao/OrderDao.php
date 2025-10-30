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
    public function createCustomOrder($userId, $categoryId, $details, $price) {
        $data = [
            "user_id" => $userId,
            "category_id" => $categoryId,
            "details" => $details,
            "total_amount" => $price,
            "status" => "Pending"
        ];
        return $this->insert($data);
    }

    /** status postojeceg ordera */
    public function updateStatus($orderId, $status) {
        return $this->update(["status" => $status], $orderId);
    }
}
?>

