<?php

Flight::route('GET /cart/@user_id', function($user_id) {
    Flight::json(Flight::cartService()->getCartByUser($user_id));
});

Flight::route('GET /cart/total/@user_id', function($user_id) {
    Flight::json(Flight::cartService()->getCartTotals($user_id));
});

Flight::route('POST /cart', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::cartService()->insertToCart($data));
});

Flight::route('PUT /cart', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::cartService()->updateQuantity($data));
});

Flight::route('DELETE /cart/item/@user_id/@product_id', function($user_id, $product_id) {
    Flight::json(Flight::cartService()->deleteFromCart($user_id, $product_id));
});

Flight::route('DELETE /cart/@user_id', function($user_id) {
    Flight::json(Flight::cartService()->clearCart($user_id));
});
?>