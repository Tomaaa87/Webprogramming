<?php

/**
 * @OA\Tag(
 *     name="Order Items",
 *     description="Order item management"
 * )
 */


/**
 * @OA\Post(
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
    $data = Flight::request()->data->getData();
    Flight::json(Flight::orderItemService()->addItem($data));
});


/**
 * @OA\Get(
 *     path="/order-items/{order_id}",
 *     tags={"Order Items"},
 *     summary="Get order summary (basic item info)",
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
    Flight::json(Flight::orderItemService()->getByOrderId($order_id));
});


/**
 * @OA\Get(
 *     path="/order-items/details/{order_id}",
 *     tags={"Order Items"},
 *     summary="Get detailed items info for an order",
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
    Flight::json(Flight::orderItemService()->getItemsByOrder($order_id));
});


/**
 * @OA\Delete(
 *     path="/order-items/{order_id}",
 *     tags={"Order Items"},
 *     summary="Delete all items for an order",
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
    Flight::json(Flight::orderItemService()->deleteByOrder($order_id));
});


/**
 * @OA\Get(
 *     path="/order-items",
 *     tags={"Order Items"},
 *     summary="Get all order items",
 *     @OA\Response(
 *         response=200,
 *         description="All order items"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /order-items', function() {
    Flight::json(Flight::orderItemService()->getAllio());
});

?>
