<?php
require_once __DIR__ . '/../dao/CustomOrderDao.php';
require_once __DIR__ . "/BaseService.php";
class CustomOrderService extends BaseService {

    public function __construct() {
        parent::__construct(new CustomOrderDao());
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