<?php

require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/UserDao.php";

class UserService extends BaseService {

    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function insertUser($user) {
        // dal već postoji korisnik sa istim emailom
        if (isset($user['email'])) {
            $existingUser = $this->dao->getByEmail($user['email']);
            if ($existingUser) {
                throw new Exception("User with this email already exists.");
            }
        }

        // email format validacija
        if (isset($user['email']) && !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // validacija formata telefonskog broja
        if (isset($user['phone']) && !empty($user['phone'])) {
            if (!preg_match('/^\+?[0-9]{9,20}$/', $user['phone'])) {
                throw new Exception("Invalid phone number format.");
            }
        }

        // rola je obavezna i može biti samo 'admin' ili 'user'
        if (!isset($user['role']) || empty($user['role'])) {
            throw new Exception("Role is required and must be 'admin' or 'user'.");
        }
        $user['role'] = strtolower(trim($user['role']));
        if (!in_array($user['role'], ['admin', 'user'])) {
            throw new Exception("Role must be 'admin' or 'user'.");
        }
        
        // hash lozinke ako je dostavljena
        if (isset($user['password']) && !empty($user['password'])) {
            $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
        }

        return $this->dao->insertUser($user);
    }

    public function getByEmail($email) {
        return $this->dao->getByEmail($email);
    }

    public function updateUser($id, $data) {
        // dal već postoji korisnik sa istim emailom ako se updejta
        if (isset($data['email'])) {
            $existingUser = $this->dao->getByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $id) {
                throw new Exception("User with this email already exists.");
            }
        }

        // validacija formata emaila 
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // validacija formata telefonskog broja
        if (isset($data['phone']) && !empty($data['phone'])) {
            if (!preg_match('/^\+?[0-9]{9,20}$/', $data['phone'])) {
                throw new Exception("Invalid phone number format.");
            }
        }

        // ako se mijenja lozinka, obavezno je hashati
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

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