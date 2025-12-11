<?php
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . "/rest/services/UserService.php";
require_once __DIR__ . "/rest/services/CategoryService.php";
require_once __DIR__ . "/rest/services/ProductService.php";
require_once __DIR__ . "/rest/services/CartService.php";
require_once __DIR__ . "/rest/services/OrderService.php";
require_once __DIR__ . "/rest/services/OrderItemService.php";
require_once __DIR__ . "/rest/services/CustomOrderService.php";
require_once __DIR__ . "/rest/services/AuthService.php";
require_once __DIR__ . "/middleware/AuthMiddleware.php";
require_once __DIR__ . "/data/roles.php";


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


Flight::register('userService', 'UserService');
Flight::register('categoryService', 'CategoryService');
Flight::register('productService', 'ProductService');
Flight::register('cartService', 'CartService');
Flight::register('orderService', 'OrderService');
Flight::register('orderItemService', 'OrderItemService');
Flight::register('customOrderService', 'CustomOrderService');
Flight::register('auth_service', 'AuthService');
Flight::register('auth_middleware', "AuthMiddleware");

Flight::before("start", function(&$params, &$output) {
    $url = Flight::request()->url;
    if(
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0 ||
        strpos($url, '/public/v1/docs') === 0 ||
        strpos($url, '/docs') === 0 ||
        preg_match('#\.(css|js|png|jpg|jpeg|svg|ico)$#i', $url)
    ) {
        return TRUE;
    } else {
        try {
            $token = Flight::request()->getHeader("Authentication");
            if(!$token) {
                $token = Flight::request()->getHeader("Authorization");
            }
            if(Flight::auth_middleware()->verifyToken($token))
                return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});


require_once __DIR__ . "/rest/routes/auth.php";
require_once __DIR__ . "/rest/routes/users.php";
require_once __DIR__ . "/rest/routes/categories.php";
require_once __DIR__ . "/rest/routes/products.php";
require_once __DIR__ . "/rest/routes/cart.php";
require_once __DIR__ . "/rest/routes/orders.php";
require_once __DIR__ . "/rest/routes/order_items.php";
require_once __DIR__ . "/rest/routes/custom_orders.php";


Flight::start();
?>