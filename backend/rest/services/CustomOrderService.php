<?php
require_once __DIR__ . '/../dao/CustomOrderDao.php';
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . "/BaseService.php";
class CustomOrderService extends BaseService {
    private $orderDao;

    public function __construct() {
        parent::__construct(new CustomOrderDao());
        $this->orderDao = new OrderDao();
    }

    public function insertCustomOrder($orderData) {
        // cijena mora biti pozitivna 
        if ($orderData["estimated_price"] <= 0) {
            throw new Exception("Estimated price must be greater than zero.");
        }

        // naslov mora biti dugacak bar 5 karaktera
        if (isset($orderData['title']) && strlen(trim($orderData['title'])) < 5) {
            throw new Exception("Custom order title must be at least 5 characters long.");
        }

        // detalji moraju biti dati i smisleni
        if (isset($orderData['details']) && strlen(trim($orderData['details'])) < 20) {
            throw new Exception("Custom order details must be at least 20 characters long.");
        }

        // Ensure we have a user_id. If client provided an order_id but no user_id,
        // try to resolve the user from the existing order record.
        if (empty($orderData['user_id'])) {
            if (!empty($orderData['order_id'])) {
                $existing = $this->orderDao->getOrderById($orderData['order_id']);
                if ($existing && isset($existing['user_id'])) {
                    $orderData['user_id'] = $existing['user_id'];
                }
            }
        }

        if (empty($orderData['user_id'])) {
            throw new Exception("User ID is required to create a custom order.");
        }

        // 1. Create a parent Order first
        $orderPayload = [
            'user_id'      => $orderData['user_id'],
            'status'       => 'Processing',
            'total_amount' => $orderData['estimated_price'],
            'is_custom'    => 1 // assuming your orders table has this flag
        ];
        
        // We need to use OrderDao to insert the parent order
        // Since we are in CustomOrderService, we can use $this->orderDao
        $newOrder = $this->orderDao->add($orderPayload);
        
        // 2. Link the new Order ID to the Custom Order data
        $orderData['order_id'] = $newOrder['id'];

        // 3. Persist into custom_orders via DAO
        return $this->dao->insertCustomOrder($orderData);
    }

    public function getAllCustomOrders() {
        return $this->dao->getAllCustomOrders();
    }
    public function getCustomOrderById($id) {
        return $this->dao->getCustomOrderById($id);
    }

    public function getByUserId($id) {
        return $this->dao->getByUserId($id);
    }

    public function deleteCustomOrder($id) {
        return $this->dao->deleteCustomOrder($id);
    }
    public function updateCustomOrder($id, $data) {
        return $this->dao->updateCustomOrder($id, $data);
    }
    public function updateStatusByCustomOrderId($customOrderId, $status) {
        $validStatuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid order status.");
        }
        $co = $this->dao->getCustomOrderById($customOrderId);
        if (!$co || !isset($co['order_id'])) {
            throw new Exception("Custom order not found.");
        }
        return $this->orderDao->update(["status" => $status], $co['order_id']);
    }
}

?>