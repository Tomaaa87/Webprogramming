<?php
require_once __DIR__ . '/../../data/roles.php';

/**
 * @OA\Tag(
 *     name="Custom Orders",
 *     description="Custom order request management"
 * )
 */

/**
 * @OA\Get(
 *     path="/custom-orders",
 *     tags={"Custom Orders"},
 *     summary="Get all custom orders",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of all custom orders"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /custom-orders', function() {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::customOrderService()->getAllCustomOrders());
});

/**
 * @OA\Get(
 *     path="/custom-orders/{id}",
 *     tags={"Custom Orders"},
 *     summary="Get a custom order by ID",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=15)),
 *     @OA\Response(response=200, description="Custom order returned"),
 *     @OA\Response(response=404, description="Custom order not found")
 * )
 */
Flight::route('GET /custom-orders/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::customOrderService()->getCustomOrderById($id));
});

/**
 * @OA\Get(
 *     path="/custom-orders/user/{user_id}",
 *     tags={"Custom Orders"},
 *     summary="Get custom orders for a specific user",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=7)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of custom orders for user"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /custom-orders/user/@user_id', function($user_id) {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::customOrderService()->getByUserId($user_id));
});

/**
 * @OA\Post(
 *     path="/custom-orders",
 *     tags={"Custom Orders"},
 *     summary="Create a custom order request",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","details","estimated_price","category"},
 *             @OA\Property(property="order_id", type="integer", example=15, description="Optional; if omitted a parent order is created automatically"),
 *             @OA\Property(property="title", type="string", example="Air suspension install"),
 *             @OA\Property(property="details", type="string", example="Installation of AirLift kit"),
 *             @OA\Property(property="estimated_price", type="number", example=5200),
 *             @OA\Property(property="category", type="string", example="Suspension"),
 *             @OA\Property(property="user_id", type="integer", example=7, description="Required; taken from token or payload")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Custom order created successfully"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /custom-orders', function() {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::customOrderService()->insertCustomOrder($data));
});

/**
 * @OA\Put(
 *     path="/custom-orders/{id}",
 *     tags={"Custom Orders"},
 *     summary="Update a custom order",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=16)),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="details", type="string"),
 *             @OA\Property(property="estimated_price", type="number")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Custom order updated")
 * )
 */
Flight::route('PUT /custom-orders/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::customOrderService()->updateCustomOrder($id, $data));
});

/**
 * @OA\Patch(
 *     path="/custom-orders/{id}/status",
 *     tags={"Custom Orders"},
 *     summary="Update linked order status by custom order id",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=16)),
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"status"}, @OA\Property(property="status", type="string", example="Processing"))),
 *     @OA\Response(response=200, description="Status updated")
 * )
 */
Flight::route('PATCH /custom-orders/@id/status', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $payload = Flight::request()->data->getData();
    Flight::json(["updated" => Flight::customOrderService()->updateStatusByCustomOrderId($id, $payload["status"])]);
});

/**
 * @OA\Delete(
 *     path="/custom-orders/{id}",
 *     tags={"Custom Orders"},
 *     summary="Delete a custom order by ID",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=16)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Custom order deleted successfully"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /custom-orders/@id', function($id) {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(["deleted" => Flight::customOrderService()->deleteCustomOrder($id)]);
});

?>
