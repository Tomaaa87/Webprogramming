<?php
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . '/../dao/CustomOrderDao.php'; // Include the CustomOrderDao
require_once __DIR__ . "/BaseService.php";

class OrderService extends BaseService {
    private $customOrderDao;

    public function __construct() {
        parent::__construct(new OrderDao());
        $this->customOrderDao = new CustomOrderDao();
    }
    
    public function getAllOrders() {
        return $this->dao->getAllOrders();
    }   
    public function getOrderById($id) {
        return $this->dao->getOrderById($id);
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
        // Support both flows: provided order_id or create a new order
        $orderId = $data['order_id'] ?? null;
        if (!$orderId) {
            $orderPayload = [
                'user_id'      => $data['user_id'],
                'total_amount' => $data['estimated_price'],
                'status'       => 'Pending',
                'is_custom'    => 1
            ];
            $newOrder = $this->dao->add($orderPayload);
            $orderId = $newOrder['id'];
        }

        $customOrderPayload = [
            'order_id'        => $orderId,
            'title'           => $data['title'],
            'details'         => $data['details'],
            'estimated_price' => $data['estimated_price'],
            'category'        => $data['category'] ?? null
        ];
        return $this->customOrderDao->add($customOrderPayload);
    }
    
    public function updateStatus($orderId, $status) {
        $validStatuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid order status.");
        }

        return $this->dao->update(["status" => $status], $orderId);
    }
    public function getByStatus($status) {
        return $this->dao->getByStatus($status);
    }
    public function getRecentOrders($limit = 10) {
        return $this->dao->getRecentOrders($limit);
    }
    
    public function getOrderWithItems($orderId) {
        return $this->dao->getOrderWithItems($orderId);
    }

    public function deleteOrder($id) {
        return $this->dao->deleteOrder($id);
    }
}
?>
