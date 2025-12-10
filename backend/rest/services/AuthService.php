<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
<<<<<<< HEAD
require_once __DIR__ . '/UserService.php';
=======
>>>>>>> 3fad2a087a54eec5544563f042fe2f2064156d80
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {
    private $auth_dao;
<<<<<<< HEAD
    private $user_service;
    public function __construct() {
        $this->auth_dao = new AuthDao();
        $this->user_service = new UserService();
=======
    public function __construct() {
        $this->auth_dao = new AuthDao();
>>>>>>> 3fad2a087a54eec5544563f042fe2f2064156d80
        parent::__construct(new AuthDao);
    }

    public function get_user_by_email($email){
        return $this->auth_dao->get_user_by_email($email);
    }

    public function register($entity) {   
<<<<<<< HEAD
=======
        
>>>>>>> 3fad2a087a54eec5544563f042fe2f2064156d80
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

<<<<<<< HEAD
        try {
            $created = $this->user_service->insertUser($entity);
            if (isset($created['password'])) { unset($created['password']); }
            return ['success' => true, 'data' => $created];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
=======
        $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
        if($email_exists){
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);

        $entity = parent::add($entity);

        unset($entity['password']);
        
        return ['success' => true, 'data' => $entity];  
                   
>>>>>>> 3fad2a087a54eec5544563f042fe2f2064156d80
    }

    public function login($entity) {   
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->auth_dao->get_user_by_email($entity['email']);
        if(!$user){
            return ['success' => false, 'error' => 'Invalid username or password.'];
        }

        if(!$user || !password_verify($entity['password'], $user['password']))
            return ['success' => false, 'error' => 'Invalid username or password.'];

        unset($user['password']);
        
        $jwt_payload = [
            'user' => $user,
            'iat' => time(),
            // If this parameter is not set, JWT will be valid for life. This is tno a good approach
            'exp' => time() + (60 * 60 * 3) // valid for 3 hours
        ];

        $token = JWT::encode(
            $jwt_payload,
            Config::JWT_SECRET(),
            'HS256'
        );

        return ['success' => true, 'data' => array_merge($user, ['token' => $token])];              
    }
}