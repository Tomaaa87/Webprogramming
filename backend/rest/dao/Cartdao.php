<?php
require_once 'BaseDao.php';

/**
 * Dao za kart
 * 
 */
class CartDao extends BaseDao {

    public function __construct() {
        parent::__construct("cart");
    }

    /**
     * dodaje proizvod u korpu
     * Ako isti (user_id, product_id) postoji, povecava broj komada umjesto da pravi novi red.
     */
    public function insertToCart($userId, $productId, $qty = 1, $unitPrice = null) {
        // ako cijena nije specifirana kradi iz products tabele 
        if ($unitPrice === null) {
            $product = $this->query(
                "SELECT price FROM products WHERE id = :pid",
                ["pid" => $productId]
            );
            if (!$product) {
                throw new Exception("Product not found.");
            }
            $unitPrice = $product[0]["price"];
        }

        // apdejt ako vec postoji
        $updated = $this->execute_query("
            UPDATE cart
            SET quantity = quantity + :q, unit_price = :p
            WHERE user_id = :uid AND product_id = :pid
        ", [
            "q" => $qty,
            "p" => $unitPrice,
            "uid" => $userId,
            "pid" => $productId
        ]);

        // ako nista nije updejtano dodaje novi red 
        if ($updated === 0) {
            $data = [
                "user_id" => $userId,
                "product_id" => $productId,
                "quantity" => $qty,
                "unit_price" => $unitPrice
            ];
            return $this->add($data); 
        }

        // vraca id postojeceg produkta u korpi
        $row = $this->query("
            SELECT id FROM cart WHERE user_id = :uid AND product_id = :pid
        ", [
            "uid" => $userId,
            "pid" => $productId
        ]);
        return $row ? $row[0]["id"] : null;
    }

    /**
     * sve kart iteme za korisnika
     */
    public function getCartByUser($userId) {
        return $this->query("
            SELECT c.id, c.user_id, c.product_id, c.quantity, c.unit_price,
                   (c.quantity * c.unit_price) AS line_total,
                   p.name AS product_name, p.image_url
            FROM cart c
            JOIN products p ON p.id = c.product_id
            WHERE c.user_id = :uid
            ORDER BY c.added_at DESC
        ", ["uid" => $userId]);
    }

    /** obnavlja kolicinu u korpi. */
    public function updateQuantity($cartId, $newQty) {
        return $this->update(["quantity" => $newQty], $cartId);
    }

    // Update by composite keys to match route payload
    public function updateByUserProduct($userId, $productId, $newQty) {
        return $this->execute_query(
            "UPDATE cart SET quantity = :q WHERE user_id = :uid AND product_id = :pid",
            ["q" => $newQty, "uid" => $userId, "pid" => $productId]
        );
    }

    /** brise jedan item iz karta  */
    public function deleteFromCart($cartId) {
        return $this->delete($cartId);
    }

    // Delete by composite keys to match route
    public function deleteByUserProduct($userId, $productId) {
        return $this->execute_query(
            "DELETE FROM cart WHERE user_id = :uid AND product_id = :pid",
            ["uid" => $userId, "pid" => $productId]
        );
    }

    /** Briše sve produkte iz korpe za korisnika  */
    public function clearCart($userId) {
        return $this->execute_query(
            "DELETE FROM cart WHERE user_id = :uid",
            ["uid" => $userId]
        );
    }

    /**sveukupne produkte i ukupna cijena u korpi */
    public function getCartTotals($userId) {
        $result = $this->query_unique("
            SELECT 
                COALESCE(SUM(quantity), 0) AS items_count,
                COALESCE(SUM(quantity * unit_price), 0) AS grand_total
            FROM cart 
            WHERE user_id = :uid
        ", ["uid" => $userId]);

        // Ensure that if the cart is empty, we return zeros instead of nulls
        return [
            "items_count" => $result["items_count"] ?? 0,
            "grand_total" => $result["grand_total"] ?? 0.00
        ];
    }
}
?>
