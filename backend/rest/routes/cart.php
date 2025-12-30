<?php
require_once __DIR__ . '/../../data/Roles.php';

/**
 * @OA\Tag(
 *     name="Cart",
 *     description="Shopping cart management endpoints"
 * )
 */


/**
 * @OA\Get(
 *     path="/cart",
 *     tags={"Cart"},
 *     summary="Get all carts (admin)",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of all active carts grouped by user"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /cart', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::cartService()->getAllCarts());
});


/**
 * @OA\Get(
 *     path="/cart/{user_id}",
 *     tags={"Cart"},
 *     summary="Get cart items for a user",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User cart data"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /cart/@user_id', function($user_id) {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::cartService()->getCartByUser($user_id));
});


/**
 * @OA\Get(
 *     path="/cart/total/{user_id}",
 *     tags={"Cart"},
 *     summary="Get total price and item count for a user cart",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart totals returned"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /cart/total/@user_id', function($user_id) {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::cartService()->getCartTotals($user_id));
});


/**
 * @OA\Post(
 *     path="/cart",
 *     tags={"Cart"},
 *     summary="Insert item to cart",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","product_id","quantity","unit_price"},
 *             @OA\Property(property="user_id", type="integer", example=10),
 *             @OA\Property(property="product_id", type="integer", example=3),
 *             @OA\Property(property="quantity", type="integer", example=2),
 *             @OA\Property(property="unit_price", type="number", example=330.00)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Item inserted to cart"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /cart', function() {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::cartService()->insertToCart($data));
});


/**
 * @OA\Put(
 *     path="/cart",
 *     tags={"Cart"},
 *     summary="Update cart item quantity",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","product_id","quantity"},
 *             @OA\Property(property="user_id", type="integer", example=10),
 *             @OA\Property(property="product_id", type="integer", example=3),
 *             @OA\Property(property="quantity", type="integer", example=4)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart updated successfully"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /cart', function() {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::cartService()->updateQuantity($data));
});


/**
 * @OA\Delete(
 *     path="/cart/item/{user_id}/{product_id}",
 *     tags={"Cart"},
 *     summary="Delete a single item from cart",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Parameter(
 *         name="product_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Item removed from cart"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /cart/item/@user_id/@product_id', function($user_id, $product_id) {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::cartService()->deleteFromCart($user_id, $product_id));
});


/**
 * @OA\Delete(
 *     path="/cart/{user_id}",
 *     tags={"Cart"},
 *     summary="Clear entire user cart",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User cart cleared"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /cart/@user_id', function($user_id) {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::cartService()->clearCart($user_id));
});

?>
