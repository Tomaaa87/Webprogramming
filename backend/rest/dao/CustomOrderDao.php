<?php
require_once 'BaseDao.php';

/**
 * DAO klasa za upravljanje custom narudžbama 
 
 */
class CustomOrderDao extends BaseDao {

    /**
     * BaseDao konstruktor i veže  na tabelu "custom_orders"
     */
    public function __construct() {
        parent::__construct("custom_orders");
    }
   // Unosi novu custom narudžbu u bazu
     public function insertCustomOrder($orderData) {

        return $this->add([
            "order_id"        => $orderData["order_id"],
            "user_id"         => $orderData["user_id"],
            "title"           => $orderData["title"],
            "estimated_price" => $orderData["estimated_price"],
            "details"         => $orderData["details"]
        ]);
    }


    public function getAllCustomOrders() {
        return $this->getAll();
    }
    public function getCustomOrderById($id) {
        return parent::getById($id);
    }

 
    public function getByUserId($userId) {
        return $this->query("
            SELECT co.*, u.name AS user_name
            FROM custom_orders co
            JOIN users u ON co.user_id = u.id
            WHERE co.user_id = :uid
            ORDER BY co.created_at DESC
        ", ["uid" => $userId]);
    }
    
    public function deleteCustomOrder($orderId) {
        return $this->delete($orderId);
    }
    public function updateCustomOrder($id, $data) {
        return $this->update([
            "title" => $data["title"] ?? null,
            "estimated_price" => $data["estimated_price"] ?? null,
            "details" => $data["details"] ?? null
        ], $id);
    }
}
    /**bili komentari oni za parametre(@param) greska*/