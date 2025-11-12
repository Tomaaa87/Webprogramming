<?php
require_once __DIR__ . '/../dao/ProductDao.php';
require_once __DIR__ . "/BaseService.php";

class ProductService extends BaseService {
    public function __construct() {
        parent::__construct(new ProductDao());
    }

    public function addProduct($product) {
        // cijena mora biti pozitivna
        if (isset($product['price']) && $product['price'] <= 0) {
            throw new Exception("Product price must be greater than zero.");
        }

        // stock ako se updejta ne smije biti negativan
        if (isset($product['stock']) && $product['stock'] < 0) {
            throw new Exception("Product stock cannot be negative.");
        }

        // ime bar 3 slova dugacko
        if (isset($product['name']) && strlen(trim($product['name'])) < 3) {
            throw new Exception("Product name must be at least 3 characters long.");
        }

        return $this->dao->addProduct($product);
    }

    public function updateProduct($id, $product) {
        // cijena mora biti pozitivna
        if (isset($product['price']) && $product['price'] <= 0) {
            throw new Exception("Product price must be greater than zero.");
        }

        // stock ako se updejta ne smije biti negativan
        if (isset($product['stock']) && $product['stock'] < 0) {
            throw new Exception("Product stock cannot be negative.");
        }

        // ime bar 3 slova dugacko
        if (isset($product['name']) && strlen(trim($product['name'])) < 3) {
            throw new Exception("Product name must be at least 3 characters long.");
        }

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
    
    public function getAllProducts() {
        return $this->dao->getAllProducts();
    }
}
?>