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
    // Explicit helper to add a custom order (wrapper around BaseDao::add)
    public function addCustomOrder($orderData) {
        // If user_id is not provided but order_id is, try to resolve it from orders table
        if (empty($orderData['user_id']) && !empty($orderData['order_id'])) {
            $existing = $this->query_unique("SELECT * FROM orders WHERE id = :id", ["id" => $orderData['order_id']]);
            if ($existing && isset($existing['user_id'])) {
                $orderData['user_id'] = $existing['user_id'];
            }
        }

        // Build insert payload, only include user_id if the column exists in custom_orders
        $payload = [
            "order_id"        => $orderData["order_id"],
            "title"           => $orderData["title"],
            "estimated_price" => $orderData["estimated_price"],
            "details"         => $orderData["details"],
            "category"        => $orderData["category"] ?? null
        ];

        // Check if the custom_orders table has a user_id column; if so, include it
        if ($this->columnExists('user_id')) {
            $payload['user_id'] = $orderData['user_id'] ?? null;
        }

        return parent::add($payload);
    }

    // Helper: check whether a column exists for this table
    private function columnExists($columnName) {
        $dbName = $this->connection->query('select database() as db')->fetchColumn();
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) as cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table AND COLUMN_NAME = :col"
        );
        $stmt->execute(["db" => $dbName, "table" => $this->table_name, "col" => $columnName]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row && isset($row['cnt']) && (int)$row['cnt'] > 0);
    }

   // Unosi novu custom narudžbu u bazu
     public function insertCustomOrder($orderData) {
        return $this->addCustomOrder($orderData);
    }


    public function getAllCustomOrders() {
        return $this->getAll();
    }
    public function getCustomOrderById($id) {
        return parent::getById($id);
    }

 
    public function getByUserId($userId) {
        return $this->query("
            SELECT co.*, u.name AS user_name, u.email AS user_email
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
        $fields = [];
        if (isset($data["title"])) $fields["title"] = $data["title"];
        if (isset($data["estimated_price"])) $fields["estimated_price"] = $data["estimated_price"];
        if (isset($data["details"])) $fields["details"] = $data["details"];
        
        if (empty($fields)) return null;
        
        return $this->update($fields, $id);
    }
}
    /**bili komentari oni za parametre(@param) greska*/