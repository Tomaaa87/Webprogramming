<?php

Flight::route('GET /products', function() {
    Flight::json(Flight::productService()->search(""));
});

Flight::route('GET /products/@id', function($id) {
    Flight::json(Flight::productService()->getByCategory($id));
});

Flight::route('POST /products', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::productService()->addProduct($data));
});

Flight::route('PATCH /products/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::productService()->updateProduct($id, $data));
});

Flight::route('DELETE /products/@id', function($id) {
    Flight::json(["deleted" => Flight::productService()->deleteProduct($id)]);
});

Flight::route('GET /products/all', function() {
    Flight::json(Flight::productService()->getAllProducts());
});

?>