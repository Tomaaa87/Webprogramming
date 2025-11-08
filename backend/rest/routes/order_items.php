<?php

Flight::route('POST /order-items', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::orderItemService()->addItem($data));
});

Flight::route('GET /order-items/@order_id', function($order_id) {
    Flight::json(Flight::orderItemService()->getByOrderId($order_id));
});

Flight::route('DELETE /order-items/@order_id', function($order_id) {
    Flight::json(Flight::orderItemService()->deleteByOrder($order_id));
});
?>