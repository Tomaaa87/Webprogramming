<?php

Flight::route('GET /orders', function() {
    Flight::json(Flight::orderService()->getAllOrders());
});

Flight::route('GET /orders/@id', function($id) {
    Flight::json(Flight::orderService()->getById($id));
});

Flight::route('GET /orders/user/@user_id', function($user_id) {
    Flight::json(Flight::orderService()->getOrdersByUser($user_id));
});

Flight::route('GET /orders/total/@id', function($id) {
    Flight::json(Flight::orderService()->getOrderTotal($id));
});

Flight::route('POST /orders', function() {
    $data = Flight::request()->data->getData();
    Flight::json(["order_id" => Flight::orderService()->insertOrder($data)]);
});

Flight::route('PUT /orders/@id/status', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::orderService()->updateStatus($id, $data));
});

Flight::route('DELETE /orders/@id', function($id) {
    Flight::json(["deleted" => Flight::orderService()->deleteOrder($id)]);
});

?>