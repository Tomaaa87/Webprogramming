<?php
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . "/BaseService.php";
class OrderItemService {

    private $dao;

    public function __construct() {
        $this->dao = new OrderItemDao();
    }

    public function addItem($orderId, $productId, $quantity, $price) {
        if (!isset($orderId) || !isset($price)) {
            throw new Exception("order_id i price su obavezni.");
        }

        return $this->dao->addItem([
            "order_id" => $orderId,
            "product_id" => $productId,
            "quantity" => $quantity,
            "price" => $price
        ]);
    }

    public function getByOrderId($id) {
        return $this->dao->getByOrderId($id);
    }
    public function getAllio() {
        return $this->dao->getAllio();
    }
    public function getItemsByOrder($orderId) {
        return $this->dao->getItemsByOrder($orderId);
    }
    public function deleteByOrder($orderId) {
        return $this->dao->deleteByOrder($orderId);
    }
}
?>
