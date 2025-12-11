<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao {

    public function __construct() {
        parent::__construct("users");
    }

    
    public function insertUser($data) {
        return $this->add($data);
    }


    public function getByEmail($email) {
        return $this->query_unique(
            "SELECT * FROM users WHERE email = :email",
            ["email" => $email]
        );
    }

    
    public function deleteUser($id) {
        return $this->delete($id);
    }
    public function getById($id) {
        return parent::getById($id); 
    }


    public function updateUser($id, $data) {
        return $this->update($data, $id);
    }

    public function getAllUsers() {
        return $this->getAll();
    }

    public function getUsersByRole($role) {
        return $this->query("SELECT * FROM users WHERE role = :role ORDER BY id DESC", ["role" => $role]);
    }
    public function searchUsers($term) {
        $like = "%" . $term . "%";
        return $this->query("SELECT * FROM users WHERE name LIKE :like OR email LIKE :like ORDER BY id DESC", ["like" => $like]);
    }
}
?>
