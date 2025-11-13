<?php

require_once __DIR__ . '/../rest/services/UserService.php';
require_once __DIR__ . '/../rest/services/ProductService.php';
require_once __DIR__ . '/../rest/services/CategoryService.php';
require_once __DIR__ . '/../rest/services/CartService.php';
require_once __DIR__ . '/../rest/services/OrderService.php';
require_once __DIR__ . '/../rest/services/OrderItemService.php';
require_once __DIR__ . '/../rest/services/CustomOrderService.php';

echo "<h2>===== SERVICE UNIT TESTS =====</h2>";

try {
    /** USER SERVICE TEST */
    echo "<b>USER SERVICE TEST</b><br>";
    $userService = new UserService();

    $user = $userService->insertUser([
        "name" => "Test User",
        "email" => "test@example.com",
        "password" => "1234"
    ]);

    $fetched = $userService->getByEmail("test@example.com");
    echo "Fetched by email: " . $fetched['name'] . "<br>";
/** CATEGORY SERVICE TEST */
echo "<br><b>CATEGORY SERVICE TEST (CRUD)</b><br>";
$categoryService = new CategoryService();

$newCategoryId = $categoryService->add([
    "category_name" => "Test Category",
    "description" => "Category created during unit test"
]);
echo "✔ Inserted category ID: $newCategoryId<br>";

$category = $categoryService->getById($newCategoryId);
echo "Fetched Category: " . $category['category_name'] . "<br>";

$categoryService->update($newCategoryId, [
    "category_name" => "Updated Test Category",
    "description" => "Updated description"
]);
echo "✔ Updated category<br>";

$categoryService->delete($newCategoryId);
echo "✔ Deleted category<br>";


/** PRODUCT SERVICE TEST */
echo "<br><b>PRODUCT SERVICE TEST (CRUD)</b><br>";
$productService = new ProductService();

$newProductId = $productService->add([
    "name" => "Unit Test Product",
    "price" => 123.45,
    "description" => "Inserted via ProductService test",
    "category_id" => 1,
    "image_url" => "./assets/images/test.png"
]);
echo "✔ Inserted product ID: $newProductId<br>";

$product = $productService->getById($newProductId);
echo "Fetched Product: " . $product['name'] . "<br>";

$productService->update($newProductId, [
    "name" => "Updated Test Product",
    "price" => 200.99,
    "description" => "Updated description",
    "category_id" => 1,
    "image_url" => "./assets/images/test_updated.png"
]);
echo "✔ Updated product<br>";

$productService->delete($newProductId);
echo "✔ Deleted product<br>";

    /** CART SERVICE TEST */
    echo "<br><b>CART SERVICE TEST</b><br>";
    $cartService = new CartService();
    $cartService->insertToCart([
        "user_id" => $user['id'],
        "product_id" => 1,
        "quantity" => 2,
        "unit_price" => 199.99
    ]);
    echo "✔ Added item to cart<br>";

    $userCart = $cartService->getCartItems($user['id']);
    echo "Items in cart: " . count($userCart) . "<br>";

    /** ORDER SERVICE + ORDER ITEM SERVICE TEST */
    echo "<br><b>ORDER SERVICE TEST</b><br>";
    $orderService = new OrderService();
    $orderId = $orderService->createOrder([
        "user_id" => $user['id'],
        "total_amount" => 399.98,
        "is_custom" => 0
    ]);
    echo "Created order ID: {$orderId}<br>";

    $orderItemService = new OrderItemService();
    $orderItemService->addItems([
        ["order_id" => $orderId, "product_id" => 1, "quantity" => 2, "price" => 199.99]
    ]);
    echo "✔ Inserted order items<br>";

    /** CUSTOM ORDER SERVICE TEST */
    echo "<br><b>CUSTOM ORDER SERVICE TEST</b><br>";
    $customOrderService = new CustomOrderService();
    $customOrderService->createCustomOrder([
        "order_id" => $orderId,
        "title" => "Custom Service Test Order",
        "details" => "Testing custom order creation",
        "estimated_price" => 123.45
    ]);
    echo "✔ Custom order created<br>";

    echo "<h3>✅ ALL SERVICE TESTS PASSED</h3>";

} catch (Exception $e) {
    echo "<h3>❌ TEST FAILED</h3>";
    echo "Error: " . $e->getMessage();
}
?>
