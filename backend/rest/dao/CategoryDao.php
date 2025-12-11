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

    /** pojedinacna kategorija po id */
    public function getCategoryById($id) {
        return $this->getById($id);
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
        return $this->add([
            "category_name" => $data["category_name"],
            "description"   => $data["description"]
        ]);}
    public function updateCategory($id, $data) {
        $fields = [];
        if (isset($data["category_name"])) $fields["category_name"] = $data["category_name"];
        if (isset($data["description"])) $fields["description"] = $data["description"];
        
        if (empty($fields)) return null;
        
        return $this->update($fields, $id);
    }
    public function deleteCategory($id) {
        return $this->delete($id);}

    
}


?>
