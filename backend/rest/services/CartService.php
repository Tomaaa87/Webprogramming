<?php
require_once __DIR__ . '/../dao/CartDao.php';
require_once __DIR__ . "/BaseService.php";
class CartService extends BaseService {

   public function __construct() {
        parent::__construct(new CartDao());
    }
    
    public function insertToCart($data) {
        if (!isset($data['user_id']) || !isset($data['product_id'])) {
            throw new Exception("user_id i product_id su obavezni.");
        }

        // kolicina pozitivna
        if (isset($data['quantity']) && $data['quantity'] <= 0) {
            throw new Exception("Quantity must be greater than zero.");
        }

        // cijena po jedinici mora biti pozitivna
        if (isset($data['unit_price']) && $data['unit_price'] <= 0) {
            throw new Exception("Unit price must be greater than zero.");
        }

        return $this->dao->insertToCart(
            $data['user_id'],
            $data['product_id'],
            $data['quantity'] ?? 1,
            $data['unit_price'] ?? null
        );
    }

    public function getCartByUser($userId) {
        return $this->dao->getCartByUser($userId);
    }

    public function getCartTotals($userId) {
        return $this->dao->getCartTotals($userId);
    }
    
    public function updateQuantity($data) {
        // Expect { user_id, product_id, quantity }
        if (!isset($data['user_id']) || !isset($data['product_id']) || !isset($data['quantity'])) {
            throw new Exception("user_id, product_id and quantity are required.");
        }
        if ($data['quantity'] <= 0) {
            throw new Exception("Quantity must be greater than zero.");
        }

        return $this->dao->updateByUserProduct($data['user_id'], $data['product_id'], $data['quantity']);
    }
    
    public function deleteFromCart($userId, $productId) {
        return $this->dao->deleteByUserProduct($userId, $productId);
    }
    
    public function clearCart($userId) {
        return $this->dao->clearCart($userId);
    }

}
?>
