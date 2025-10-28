<?php
// backend/test/test_crud.php

require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/../dao/ReviewDao.php';

echo "===== FULL DAO CRUD TEST START =====\n";

try {
    // 🧍 USERS
    echo "\n=== USERS ===\n";
    $userDao = new UserDao();

    // CREATE
    $newUserId = $userDao->insert([
        'name' => 'John Test',
        'email' => 'john.test@example.com',
        'password' => password_hash('123456', PASSWORD_DEFAULT),
        'address' => '123 Test Street',
        'phone' => '111-222'
    ]);
    echo "User created (ID: $newUserId)\n";

    // READ
    $user = $userDao->getById($newUserId, 'user_id');
    print_r($user);

    // UPDATE
    $userDao->update($newUserId, ['address' => '456 Updated Street'], 'user_id');
    echo "User updated.\n";

    // DELETE
    $userDao->delete($newUserId, 'user_id');
    echo "User deleted.\n";

    // 🗂️ CATEGORIES
    echo "\n=== CATEGORIES ===\n";
    $catDao = new CategoryDao();

    $catId = $catDao->insert(['category_name' => 'Test Category', 'description' => 'For CRUD testing']);
    echo "Category created (ID: $catId)\n";

    $cat = $catDao->getById($catId, 'category_id');
    print_r($cat);

    $catDao->update($catId, ['description' => 'Updated description'], 'category_id');
    echo "Category updated.\n";

    $catDao->delete($catId, 'category_id');
    echo "Category deleted.\n";

    // 📦 PRODUCTS
    echo "\n=== PRODUCTS ===\n";
    $prodDao = new ProductDao();

    $prodId = $prodDao->insert([
        'name' => 'Test Product',
        'price' => 15.50,
        'description' => 'Demo CRUD product',
        'category_id' => null, // optional if foreign key allows
        'image_url' => 'img/test.png'
    ]);
    echo "Product created (ID: $prodId)\n";

    $prod = $prodDao->getById($prodId, 'product_id');
    print_r($prod);

    $prodDao->update($prodId, ['price' => 20.00], 'product_id');
    echo "Product updated.\n";

    $prodDao->delete($prodId, 'product_id');
    echo "Product deleted.\n";

    // 🧾 ORDERS
    echo "\n=== ORDERS ===\n";
    $orderDao = new OrderDao();

    // Insert user again because order needs user_id
    $userId = $userDao->insert([
        'name' => 'Order Tester',
        'email' => 'ordertest@example.com',
        'password' => password_hash('123', PASSWORD_DEFAULT),
        'address' => 'Order Street',
        'phone' => '999-999'
    ]);

    $orderId = $orderDao->insert([
        'user_id' => $userId,
        'total_amount' => 100.00,
        'status' => 'Pending'
    ]);
    echo "Order created (ID: $orderId)\n";

    $order = $orderDao->getById($orderId, 'order_id');
    print_r($order);

    $orderDao->update($orderId, ['status' => 'Completed'], 'order_id');
    echo "Order updated.\n";

    $orderDao->delete($orderId, 'order_id');
    echo "Order deleted.\n";

    // 🧺 ORDER ITEMS
    echo "\n=== ORDER ITEMS ===\n";
    $orderItemDao = new OrderItemDao();

    // recreate order + product first
    $orderId = $orderDao->insert([
        'user_id' => $userId,
        'total_amount' => 50.00,
        'status' => 'Pending'
    ]);
    $prodId = $prodDao->insert([
        'name' => 'OrderItem Test Product',
        'price' => 10.00,
        'description' => 'Used in order items test',
        'category_id' => null,
        'image_url' => 'img/test2.png'
    ]);

    $itemId = $orderItemDao->insert([
        'order_id' => $orderId,
        'product_id' => $prodId,
        'quantity' => 2,
        'price' => 10.00
    ]);
    echo "OrderItem created (ID: $itemId)\n";

    $item = $orderItemDao->getById($itemId, 'order_item_id');
    print_r($item);

    $orderItemDao->update($itemId, ['quantity' => 3], 'order_item_id');
    echo "OrderItem updated.\n";

    $orderItemDao->delete($itemId, 'order_item_id');
    echo "OrderItem deleted.\n";

    // ⭐ REVIEWS
    echo "\n=== REVIEWS ===\n";
    $reviewDao = new ReviewDao();

    $reviewId = $reviewDao->insert([
        'user_id' => $userId,
        'product_id' => $prodId,
        'rating' => 5,
        'comment' => 'Great test product!'
    ]);
    echo "Review created (ID: $reviewId)\n";

    $review = $reviewDao->getById($reviewId, 'review_id');
    print_r($review);

    $reviewDao->update($reviewId, ['comment' => 'Updated comment'], 'review_id');
    echo "Review updated.\n";

    $reviewDao->delete($reviewId, 'review_id');
    echo "Review deleted.\n";

    echo "\n===== ALL DAO CRUD TESTS COMPLETED SUCCESSFULLY =====\n";

} catch (Exception $e) {
    echo "❗ Error: " . $e->getMessage() . "\n";
}
?>
