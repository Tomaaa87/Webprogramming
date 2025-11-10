<?php

Flight::route('GET /orders', function() {
    Flight::json(Flight::orderService()->getAllOrders());
});


Flight::route('POST /orders', function() {
    $data = Flight::request()->data->getData();
    Flight::json(["order_id" => Flight::orderService()->insertOrder($data)]);
});


Flight::route('GET /orders/user/@user_id', function($user_id) {
    Flight::json(Flight::orderService()->getByUserId($user_id));
});


Flight::route('POST /orders/custom', function() {
    $data = Flight::request()->data->getData();
    Flight::json(["custom_order_id" => Flight::orderService()->createCustomOrder($data)]);
});

Flight::route('PATCH /orders/@order_id/status', function($order_id) {
    $payload = Flight::request()->data->getData();
    Flight::json(["updated" => Flight::orderService()->updateStatus($order_id, $payload["status"])]);
});


Flight::route('GET /orders/@order_id/details', function($order_id) {
    Flight::json(Flight::orderService()->getOrderWithItems($order_id));
});
?>