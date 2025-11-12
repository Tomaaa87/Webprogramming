<?php
/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Order management endpoints"
 * )
 */

/**
 * @OA\Get(
 *     path="/orders",
 *     tags={"Orders"},
 *     summary="Get all orders",
 *     @OA\Response(
 *         response=200,
 *         description="List of all orders"
 *     )
 *     @OA\Response(response=500, description="Server error")
 */
Flight::route('GET /orders', function() {
    Flight::json(Flight::orderService()->getAllOrders());
});
/**
 * @OA\Post(
 *     path="/orders",
 *     tags={"Orders"},
 *     summary="Insert new order",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","status","total_amount"},
 *             @OA\Property(property="user_id", type="integer", example=5),
 *             @OA\Property(property="status", type="string", example="Pending"),
 *             @OA\Property(property="total_amount", type="number", example=1299.99)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order inserted successfully"
 *     )
 *     @OA\Response(response=500, description="Server error")
 * )
 */

Flight::route('POST /orders', function() {
    $data = Flight::request()->data->getData();
    Flight::json(["order_id" => Flight::orderService()->insertOrder($data)]);
});
/**
 * @OA\Get(
 *     path="/orders/user/{user_id}",
 *     tags={"Orders"},
 *     summary="Get all orders for a specific user",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Orders returned"
 *     )
 *    @OA\Response(response=500, description="Server error")
 * )
 */

Flight::route('GET /orders/user/@user_id', function($user_id) {
    Flight::json(Flight::orderService()->getByUserId($user_id));
});

/**
 * @OA\Post(
 *     path="/orders/custom",
 *     tags={"Orders"},
 *     summary="Create a custom order",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"order_id","title","details","estimated_price","category"},
 *             @OA\Property(property="order_id", type="integer", example=13),
 *             @OA\Property(property="title", type="string", example="2976 Turbo Upgrade"),
 *             @OA\Property(property="details", type="string", example="Full turbo kit install + tuning"),
 *             @OA\Property(property="estimated_price", type="number", example=7800),
 *             @OA\Property(property="category", type="string", example="Performance Upgrades")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Custom order created successfully"
 *     )
 *     @OA\Response(response=500, description="Server error")
 */
Flight::route('POST /orders/custom', function() {
    $data = Flight::request()->data->getData();
    Flight::json(["custom_order_id" => Flight::orderService()->createCustomOrder($data)]);
});
/**
 * @OA\Patch(
 *     path="/orders/{order_id}/status",
 *     tags={"Orders"},
 *     summary="Update an order status",
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=13)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"status"},
 *             @OA\Property(property="status", type="string", example="Processing")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order status updated"
 *     )
 *    @OA\Response(response=500, description="Server error")
 * )
 */

Flight::route('PATCH /orders/@order_id/status', function($order_id) {
    $payload = Flight::request()->data->getData();
    Flight::json(["updated" => Flight::orderService()->updateStatus($order_id, $payload["status"])]);
});
/**
 * @OA\Get(
 *     path="/orders/{order_id}/details",
 *     tags={"Orders"},
 *     summary="Get an order including its items",
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=13)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order details returned"
 *     )
 *    @OA\Response(response=500, description="Server error")
 * )
 */


Flight::route('GET /orders/@order_id/details', function($order_id) {
    Flight::json(Flight::orderService()->getOrderWithItems($order_id));
});
?>