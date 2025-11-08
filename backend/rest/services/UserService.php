<?php

require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/UserDao.php";

class UserService extends BaseService {

    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function insertUser($user) {
        return $this->dao->insertUser($user);
    }

    public function getByEmail($email) {
        return $this->dao->getByEmail($email);
    }

    public function updateUser($id, $data) {
        return $this->dao->updateUser($id, $data);
    }

    public function deleteUser($id) {
        return $this->dao->deleteUser($id);
    }
    public function getAllUsers() {
        return $this->dao->getAllUsers();
    }
}

?>