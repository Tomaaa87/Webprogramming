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
        return $this->get_all();
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

}
?>
