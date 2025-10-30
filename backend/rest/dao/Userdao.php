<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao {

    public function __construct() {
        parent::__construct("users");
    }

    
    public function insertUser($data) {
        return $this->insert($data);
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


    public function updateUser($id, $data) {
        return $this->update($id, $data);
    }

    public function getAllUsers() {
        return $this->getAll();
    }
}
?>
