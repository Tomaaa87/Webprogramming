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

        return $this->dao->insertToCart($data);
    }

    public function getCartByUser($userId) {
        return $this->dao->getCartByUser($userId);
    }

    public function getCartTotals($userId) {
        return $this->dao->getCartTotals($userId);
    }
    
    public function updateQuantity($cartId, $newQty) {
        // kolicina pozitivna
        if ($newQty <= 0) {
            throw new Exception("Quantity must be greater than zero.");
        }

        return $this->dao->updateQuantity($cartId, $newQty);
    }
    
    public function deleteFromCart($cartId) {
        return $this->dao->deleteFromCart($cartId);
    }
    
    public function clearCart($userId) {
        return $this->dao->clearCart($userId);
    }

}
?>
