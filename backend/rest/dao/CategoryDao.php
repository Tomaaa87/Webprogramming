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

    /** sve kategorije */
    public function getAllCategories() {
        return $this->getAll();
    }

    /**  trazi kategorije gdje je ime slicno */
    public function searchByName($name) {
        $like = "%" . $name . "%";
        return $this->query(
            "SELECT * FROM categories WHERE category_name LIKE :name",
            ["name" => $like]
        );
    }
    public function insertCategory($data) {
        return $this->insert([
            "category_name" => $data["category_name"],
            "description"   => $data["description"]
        ]);}
    public function updateCategory($id, $data) {
        return $this->update($id, [
            "category_name" => $data["category_name"],
            "description"   => $data["description"]
        ]);
    }
    public function deleteCategory($id) {
        return $this->delete($id);}

    
}


?>
