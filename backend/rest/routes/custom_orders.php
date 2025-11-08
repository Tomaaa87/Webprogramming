<?php

Flight::route('GET /custom-orders', function() {
    Flight::json(Flight::customOrderService()->getAllCustomOrders());
});

Flight::route('GET /custom-orders/user/@user_id', function($user_id) {
    Flight::json(Flight::customOrderService()->getByUserId($user_id));
});

Flight::route('POST /custom-orders', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::customOrderService()->insertCustomOrder($data));
});
?>