<?php
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . "/BaseService.php";
class OrderService  extends BaseService {

     public function __construct() {
        parent::__construct(new OrderDao());
    }
    
    public function getAllOrders() {
        return $this->dao->getAllOrders();
    }   
    
    public function insertOrder($data) {
        // Validacija ukupnog iznosa narudžbe
        if (isset($data['total_amount']) && $data['total_amount'] < 0) {
            throw new Exception("Total amount cannot be negative.");
        }

        return $this->dao->insertOrder($data);
    }

    public function getByUserId($id) {
        return $this->dao->getByUserId($id);
    }
    
    public function createCustomOrder($data) {
        return $this->dao->createCustomOrder($data);
    }
    
    public function updateStatus($orderId, $status) {
        // Validacija statusa narudžbe
        $validStatuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid order status.");
        }

        return $this->dao->update($orderId, ["status" => $status]);
    }
    
    public function getOrderWithItems($orderId) {
        return $this->dao->getOrderWithItems($orderId);
    }

}
?>
