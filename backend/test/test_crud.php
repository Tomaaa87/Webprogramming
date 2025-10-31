<?php
require_once __DIR__ . '/../rest/dao/UserDao.php';
require_once __DIR__ . '/../rest/dao/CategoryDao.php';
require_once __DIR__ . '/../rest/dao/ProductDao.php';
require_once __DIR__ . '/../rest/dao/OrderDao.php';
require_once __DIR__ . '/../rest/dao/OrderItemDao.php';
require_once __DIR__ . '/../rest/dao/CartDao.php';
require_once __DIR__ . '/../rest/dao/CustomOrderDao.php';

echo "===== TEST START =====<br>";

try {
    // Inicijalizacija DAO-a
    $userDao = new UserDao();
    $catDao = new CategoryDao();
    $prodDao = new ProductDao();
    $orderDao = new OrderDao();
    $orderItemDao = new OrderItemDao();
    $cartDao = new CartDao();
    $customDao = new CustomOrderDao();

    echo "<b>✔️ Connection success!</b><br><br>";

    // === USERS TEST ===
    echo "<b>USERS:</b><br>";
    $users = $userDao->getAll();
    echo "Fetched " . count($users) . " users<br>";

    // === CATEGORIES TEST ===
    echo "<b>CATEGORIES:</b><br>";
    $categories = $catDao->getAll();
    echo "Fetched " . count($categories) . " categories<br>";

    // === PRODUCTS TEST ===
    echo "<b>PRODUCTS:</b><br>";
    $products = $prodDao->getAll();
    echo "Fetched " . count($products) . " products<br>";

    // === ORDERS TEST ===
    echo "<b>ORDERS:</b><br>";
    $orders = $orderDao->getAll();
    echo "Fetched " . count($orders) . " orders<br>";

    // === CART TEST ===
    echo "<b>CART:</b><br>";
    $cart = $cartDao->getAll();
    echo "Fetched " . count($cart) . " cart entries<br>";

    // === CUSTOM ORDERS TEST ===
    echo "<b>CUSTOM ORDERS:</b><br>";
    $customs = $customDao->getAllCustomOrders();
    echo "Fetched " . count($customs) . " custom orders<br>";

    echo "<br><b>✅ ALL DAO TESTS PASSED (no connection or syntax errors)</b>";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
