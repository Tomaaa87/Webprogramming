<?php
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . '/../dao/CustomOrderDao.php'; // Include the CustomOrderDao
require_once __DIR__ . '/../dao/ProductDao.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . "/BaseService.php";

class OrderService extends BaseService {
    private $customOrderDao;
    private $productDao;
    private $orderItemDao;

    public function __construct() {
        parent::__construct(new OrderDao());
        $this->customOrderDao = new CustomOrderDao();
        $this->productDao = new ProductDao();
        $this->orderItemDao = new OrderItemDao();
    }
    
    public function getAllOrders() {
        return $this->dao->getAllOrders();
    }   
    public function getOrderById($id) {
        return $this->dao->getOrderById($id);
    }
    
    public function insertOrder($data) {
        // $data should have 'user_id', 'status', 'items' (array of {product_id, quantity})
        
        $items = $data['items'] ?? [];
        if (empty($items)) {
            // Fallback for old behavior if no items provided, though user asked for "everything"
            // If total_amount is provided manually, we might accept it, but let's enforce items for "everything"
            if (isset($data['total_amount'])) {
                 return $this->dao->insertOrder($data);
            }
            throw new Exception("Order must have at least one item.");
        }

        $totalAmount = 0;
        $orderItems = [];

        foreach ($items as $item) {
            $product = $this->productDao->getProductById($item['product_id']);
            if (!$product) {
                throw new Exception("Product with ID " . $item['product_id'] . " not found.");
            }
            $price = $product['price'];
            $quantity = $item['quantity'];
            $totalAmount += $price * $quantity;
            
            $orderItems[] = [
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'price' => $price
            ];
        }

        $orderData = [
            'user_id' => $data['user_id'],
            'status' => $data['status'] ?? 'Pending',
            'total_amount' => $totalAmount
        ];

        // Insert Order
        $order = $this->dao->insertOrder($orderData);
        $orderId = $order['id'];

        // Insert Order Items
        foreach ($orderItems as $oi) {
            $this->orderItemDao->addItem($orderId, $oi['product_id'], $oi['quantity'], $oi['price']);
        }

        return $orderId;
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
