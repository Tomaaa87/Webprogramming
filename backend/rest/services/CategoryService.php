<?php

require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/CategoryDao.php";

class CategoryService extends BaseService {

    public function __construct() {
        parent::__construct(new CategoryDao());
    }
    
    public function searchByName($name) {
        return $this->dao->getByName($name);
    }
    
    public function getAllCategories() {
        return $this->dao->getAllCategories();
    }
    
    public function insertCategory($data) {
        // duzina imena kategorije bar 3 slova
        if (isset($data['category_name']) && strlen(trim($data['category_name'])) < 3) {
            throw new Exception("Category name must be at least 3 characters long.");
        }

        // opis kategorije mora biti dugacak bar 10 karaktera
        if (isset($data['description']) && strlen(trim($data['description'])) < 10) {
            throw new Exception("Category description must be at least 10 characters long.");
        }

        return $this->dao->insertCategory($data);
    }
    
    public function updateCategory($id, $data) {
        // duzina imena kategorije bar 3 slova ako se updejta
        if (isset($data['category_name']) && strlen(trim($data['category_name'])) < 3) {
            throw new Exception("Category name must be at least 3 characters long.");
        }

        // opis kategorije mora biti dugacak bar 10 karaktera ako se updejta
        if (isset($data['description']) && strlen(trim($data['description'])) < 10) {
            throw new Exception("Category description must be at least 10 characters long.");
        }

        return $this->dao->updateCategory($id, $data);
    }
    
    public function deleteCategory($id) {
        return $this->dao->deleteCategory($id);
    }
}
?>
