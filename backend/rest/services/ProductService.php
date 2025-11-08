<?php
require_once __DIR__ . '/../dao/ProductDao.php';
require_once __DIR__ . "/BaseService.php";

class ProductService extends BaseService {
    public function __construct() {
        parent::__construct(new ProductDao());
    }

    public function addProduct($product) {
        return $this->dao->addProduct($product);
    }

    public function updateProduct($id, $product) {
        return $this->dao->updateProduct($id, $product);
    }

    public function search($term) {
        return $this->dao->search($term);
    }

    public function getByCategory($categoryId) {
        return $this->dao->getByCategory($categoryId);
    }

    public function deleteProduct($id) {
        return $this->dao->deleteProduct($id);
    }
}
?>