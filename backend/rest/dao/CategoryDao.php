<?php
require_once 'BaseDao.php';

/**
 * DAO za categorie i za crud operacije
 * reusa basedao funckije gjde je moguce
 */
class CategoryDao extends BaseDao {
    public function __construct() {
        // prosljeduje ime tabele base dao 
        parent::__construct("categories");
    }

    private $selectColumns = "id, category_name, description, added_at";

    /** sve kategorije */
    public function getAllCategories() {
        return $this->query("SELECT {$this->selectColumns} FROM categories");
    }

    /** pojedinacna kategorija po id */
    public function getCategoryById($id) {
        return $this->query_unique(
            "SELECT {$this->selectColumns} FROM categories WHERE id = :id",
            ["id" => $id]
        );
    }
    
    /**  trazi kategorije gdje je ime slicno */
    public function searchByName($name) {
        $like = "%" . $name . "%";
        return $this->query(
            "SELECT {$this->selectColumns} FROM categories WHERE category_name LIKE :name",
            ["name" => $like]
        );
    }
    public function insertCategory($data) {
        return $this->add($data);
    }
    public function updateCategory($id, $data) {
        return $this->update($data, $id);
    }
    public function deleteCategory($id) {
        return $this->delete($id);}

    
}


?>
