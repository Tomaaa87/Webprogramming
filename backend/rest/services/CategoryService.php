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
        return $this->dao->insertCategory($data);
    }
    public function updateCategory($id, $data) {
        return $this->dao->updateCategory($id, $data);
    }
    public function deleteCategory($id) {
        return $this->dao->deleteCategory($id);
    }
}
?>
