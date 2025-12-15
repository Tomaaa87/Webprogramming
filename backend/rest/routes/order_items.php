<?php
require_once __DIR__ . '/../../data/roles.php';

/**
 * @OA\Tag(
 *     name="Order Items",
 *     description="Order item management"
 * )
 */


/**
 * @OA\Post(
 *     security={{"ApiKey": {}}},
 *     path="/order-items",
 *     tags={"Order Items"},
 *     summary="Add item to an order",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"order_id","product_id","quantity","price"},
 *             @OA\Property(property="order_id", type="integer", example=15),
 *             @OA\Property(property="product_id", type="integer", example=3),
 *             @OA\Property(property="quantity", type="integer", example=2),
 *             @OA\Property(property="price", type="number", example=299.99)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order item added"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /order-items', function() {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::orderItemService()->addItem($data));
});


/**
 * @OA\Get(
 *     path="/order-items/{order_id}",
 *     tags={"Order Items"},
 *     summary="Get order summary (basic item info)",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=15)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order items returned"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /order-items/@order_id', function($order_id) {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::orderItemService()->getByOrderId($order_id));
});


/**
 * @OA\Get(
 *     path="/order-items/details/{order_id}",
 *     tags={"Order Items"},
 *     summary="Get detailed items info for an order",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=15)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detailed order items returned"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /order-items/details/@order_id', function($order_id) {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::orderItemService()->getItemsByOrder($order_id));
});


/**
 * @OA\Delete(
 *     path="/order-items/{order_id}",
 *     tags={"Order Items"},
 *     summary="Delete all items for an order",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=15)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order items deleted"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /order-items/@order_id', function($order_id) {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::orderItemService()->deleteByOrder($order_id));
});


/**
 * @OA\Get(
 *     path="/order-items",
 *     tags={"Order Items"},
 *     summary="Get all order items",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="All order items"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /order-items', function() {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::orderItemService()->getAllio());
});

/**
 * @OA\Patch(
 *     path="/order-items/{id}",
 *     tags={"Order Items"},
 *     summary="Update item quantity",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"quantity"},
 *             @OA\Property(property="quantity", type="integer", example=5)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Item quantity updated")
 * )
 */
Flight::route('PATCH /order-items/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::orderItemService()->updateQuantity($id, $data['quantity']));
});

/**
 * @OA\Delete(
 *     path="/order-items/item/{id}",
 *     tags={"Order Items"},
 *     summary="Delete a single order item",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Item deleted")
 * )
 */
Flight::route('DELETE /order-items/item/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::orderItemService()->deleteItem($id));
});

/**
 * @OA\Get(
 *     path="/order-items/total/{order_id}",
 *     tags={"Order Items"},
 *     summary="Calculate total for an order",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(name="order_id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Order total")
 * )
 */
Flight::route('GET /order-items/total/@order_id', function($order_id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::orderItemService()->getTotalByOrder($order_id));
});

/**
 * @OA\Get(
 *     path="/order-items/product/{product_id}/quantity",
 *     tags={"Order Items"},
 *     summary="Get total quantity of a product across all orders",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(name="product_id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Total quantity")
 * )
 */
Flight::route('GET /order-items/product/@product_id/quantity', function($product_id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::orderItemService()->getQuantityByProduct($product_id));
});

?>
