<?php
require_once __DIR__ . '/../dao/CustomOrderDao.php';
require_once __DIR__ . "/BaseService.php";
class CustomOrderService extends BaseService {

    public function __construct() {
        parent::__construct(new CustomOrderDao());
    }

    public function insertCustomOrder($orderData) {

        if ($orderData["estimated_price"] <= 0) {
            throw new Exception("Estimated price must be greater than zero.");
        }

        return $this->create($orderData);

    }

    public function getAllCustomOrders() {
        return $this->dao->getAllCustomOrders();
    }

    public function getByUserId($id) {
        return $this->dao->getByUserId($id);
    }
}

?>