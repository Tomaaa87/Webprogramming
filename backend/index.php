<?php
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . "/rest/services/UserService.php";
require_once __DIR__ . "/rest/services/CategoryService.php";
require_once __DIR__ . "/rest/services/ProductService.php";
require_once __DIR__ . "/rest/services/CartService.php";
require_once __DIR__ . "/rest/services/OrderService.php";
require_once __DIR__ . "/rest/services/OrderItemService.php";
require_once __DIR__ . "/rest/services/CustomOrderService.php";


Flight::register('userService', 'UserService');
Flight::register('categoryService', 'CategoryService');
Flight::register('productService', 'ProductService');
Flight::register('cartService', 'CartService');
Flight::register('orderService', 'OrderService');
Flight::register('orderItemService', 'OrderItemService');
Flight::register('customOrderService', 'CustomOrderService');


require_once __DIR__ . "/rest/routes/users.php";
require_once __DIR__ . "/rest/routes/categories.php";
require_once __DIR__ . "/rest/routes/products.php";
require_once __DIR__ . "/rest/routes/cart.php";
require_once __DIR__ . "/rest/routes/orders.php";
require_once __DIR__ . "/rest/routes/order_items.php";
require_once __DIR__ . "/rest/routes/custom_orders.php";


Flight::start();
?>