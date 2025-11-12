<?php

/**
 * @OA\Tag(
 *     name="Users",
 *     description="User management endpoints"
 * )
 */

/**
 * @OA\Get(
 *     path="/users",
 *     tags={"Users"},
 *     summary="Get all users",
 *     @OA\Response(
 *         response=200,
 *         description="List of all users"
 *     )
 * )
 */
Flight::route('GET /users', function() {
    Flight::json(Flight::userService()->getAllUsers());
});


/**
 * @OA\Get(
 *     path="/users/email/{email}",
 *     tags={"Users"},
 *     summary="Get user by email",
 *     @OA\Parameter(
 *         name="email",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", example="john.doe@example.com")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User matching email returned"
 *     )
 * )
 */
Flight::route('GET /users/email/@email', function($email) {
    Flight::json(Flight::userService()->getByEmail($email));
});


/**
 * @OA\Post(
 *     path="/users",
 *     tags={"Users"},
 *     summary="Insert a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", example="test123"),
 *             @OA\Property(property="address", type="string", example="Sarajevo"),
 *             @OA\Property(property="phone", type="string", example="+38761123456"),
 *             @OA\Property(property="role", type="string", example="user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User created successfully"
 *     )
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /users', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->insertUser($data));
});


/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Update a user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="New Name"),
 *             @OA\Property(property="email", type="string", example="changed@mail.com"),
 *             @OA\Property(property="password", type="string", example="newpass123"),
 *             @OA\Property(property="address", type="string", example="Mostar"),
 *             @OA\Property(property="phone", type="string", example="+38762222333"),
 *             @OA\Property(property="role", type="string", example="admin")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     )
 *    @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /users/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->updateUser($id, $data));
});


/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"Users"},
 *     summary="Delete user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     )
 *    @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /users/@id', function($id) {
    Flight::json(["deleted" => Flight::userService()->deleteUser($id)]);
});

?>
