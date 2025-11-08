<?php

Flight::route('GET /users', function() {
    Flight::json(Flight::userService()->getAllUsers());
});

Flight::route('GET /users/email/@email', function($email) {
    Flight::json(Flight::userService()->getByEmail($email));
});

Flight::route('POST /users', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->insertUser($data));
});

Flight::route('PUT /users/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->updateUser($id, $data));
});

Flight::route('DELETE /users/@id', function($id) {
    Flight::json(["deleted" => Flight::userService()->deleteUser($id)]);
});

?>