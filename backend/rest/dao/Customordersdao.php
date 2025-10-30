<?php
require_once 'BaseDao.php';

/**
 * DAO klasa za upravljanje custom narudžbama 
 
 */
class CustomOrderDao extends BaseDao {

    /**
     * BaseDao konstruktor i veže  na tabelu "custom_orders"
     */
    public function __construct() {
        parent::__construct("custom_orders");
    }

    /**
     * Dohvaća sve custom narudžbe iz baze
     * @return array
     */
    public function getAllCustomOrders() {
        return $this->getAll();
    }

    /**
     * Dohvaća sve custom narudžbe određenog korisnika
     * 
     * @param int $userId - ID korisnika
     * @return array - Lista narudžbi povezana s korisnikom
     */
    public function getByUserId($userId) {
        return $this->query("
            SELECT co.*, u.name AS user_name
            FROM custom_orders co
            JOIN orders o ON co.order_id = o.id
            JOIN users u ON o.user_id = u.id
            WHERE u.id = :uid
            ORDER BY co.created_at DESC
        ", ["uid" => $userId]);
    }

    /**
     * Unosi novu custom narudžbu u bazu
     * 
     * @param int $orderId - ID narudžbe (iz tablice orders)
     * @param string $title - Naziv projekta / narudžbe
     * @param string $details - Opis što korisnik želi
     * @param float $estimated_price - Procijenjena cijena
     * @param string $category - Kategor*
