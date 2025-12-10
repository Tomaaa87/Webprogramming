<?php
require_once 'BaseDao.php';

/**
 * DAO for managing products.
 * Fully compatible with BaseDao generic CRUD.
 */
class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct("products");
    }

    /** uzima sve proizvode */
    public function getAllProducts() {
<<<<<<< HEAD
        return parent::getAll();
=======
        return $this->getAll();
>>>>>>> 3fad2a087a54eec5544563f042fe2f2064156d80
    }
    public function getProductById($id) {
        return $this->getById($id);
    }

    /**svi produkti po category id */
   public function getByCategory($categoryId) {
    return $this->query(
        "SELECT * FROM products WHERE category_id = :category_id",
        ["category_id" => $categoryId]
    );
}


    /** trazi proizvode po imenu ili opisu */
    public function search($term) {
    $likeTerm = "%" . $term . "%";
    return $this->query(
        "SELECT * 
         FROM products
         WHERE name LIKE :likeTerm OR description LIKE :likeTerm",
        ["likeTerm" => $likeTerm]
    );
}
    public function addProduct($product) {
        return $this->add($product);
     }
    public function updateProduct($id, $product) {
        return $this->update($product, $id);
     }
    public function getProductsInStock() {
        return $this->query("SELECT * FROM products WHERE stock > 0 ORDER BY added_at DESC");
    }
    public function updateStock($id, $stock) {
        return $this->update(["stock" => $stock], $id);
    }
    public function deleteProduct($id) {
        return $this->delete($id);
     }
    }
?>
