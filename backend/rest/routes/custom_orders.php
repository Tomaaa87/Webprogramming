<?php

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
 *     @OA\Response(
 *         response=200,
 *         description="List of all custom orders"
 *     )
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /custom-orders', function() {
    Flight::json(Flight::customOrderService()->getAllCustomOrders());
});

/**
 * @OA\Get(
 *     path="/custom-orders/user/{user_id}",
 *     tags={"Custom Orders"},
 *     summary="Get custom orders for a specific user",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=7)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of custom orders for user"
 *     )
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /custom-orders/user/@user_id', function($user_id) {
    Flight::json(Flight::customOrderService()->getByUserId($user_id));
});

/**
 * @OA\Post(
 *     path="/custom-orders",
 *     tags={"Custom Orders"},
 *     summary="Create a custom order request",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"order_id","title","details","estimated_price","category"},
 *             @OA\Property(property="order_id", type="integer", example=15),
 *             @OA\Property(property="title", type="string", example="Air suspension install"),
 *             @OA\Property(property="details", type="string", example="Installation of AirLift kit"),
 *             @OA\Property(property="estimated_price", type="number", example=5200),
 *             @OA\Property(property="category", type="string", example="Suspension")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Custom order created successfully"
 *     )
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /custom-orders', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::customOrderService()->insertCustomOrder($data));
});

?>
