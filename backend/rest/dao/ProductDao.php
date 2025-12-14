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
        return $this->query("SELECT * FROM products");
    }

    public function getProductById($id) {
        return $this->query_unique(
            "SELECT * FROM products WHERE id = :id",
            ["id" => $id]
        );
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
        $this->execute_query(
            "UPDATE products SET stock = :stock WHERE id = :id",
            ["stock" => $stock, "id" => $id]
        );
        return $this->query_unique(
            "SELECT * FROM products WHERE id = :id",
            ["id" => $id]
        );
    }
    public function deleteProduct($id) {
        return $this->delete($id);
     }
    }
?>
