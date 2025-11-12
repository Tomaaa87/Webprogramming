<?php

/**
 * @OA\Tag(
 *     name="Products",
 *     description="Product management endpoints"
 * )
 */


/**
 * @OA\Get(
 *     path="/products",
 *     tags={"Products"},
 *     summary="Search products (empty string returns all)",
 *     @OA\Response(
 *         response=200,
 *         description="List of products"
 *     )
 *    @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /products', function() {
    Flight::json(Flight::productService()->search(""));
});


/**
 * @OA\Get(
 *     path="/products/{id}",
 *     tags={"Products"},
 *     summary="Get products by category",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Products for selected category returned"
 *     )
 *    @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /products/@id', function($id) {
    Flight::json(Flight::productService()->getByCategory($id));
});


/**
 * @OA\Post(
 *     path="/products",
 *     tags={"Products"},
 *     summary="Add a new product",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","price","description","category_id"},
 *             @OA\Property(property="name", type="string", example="Turbo intake"),
 *             @OA\Property(property="price", type="number", example=985.00),
 *             @OA\Property(property="description", type="string", example="Increases air flow"),
 *             @OA\Property(property="category_id", type="integer", example=2),
 *             @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product added successfully"
 *     )
 *     @OA\Response(response=500, description="Server error")
 */
Flight::route('POST /products', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::productService()->addProduct($data));
});


/**
 * @OA\Patch(
 *     path="/products/{id}",
 *     tags={"Products"},
 *     summary="Update an existing product",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=4)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Updated product name"),
 *             @OA\Property(property="price", type="number", example=750.00),
 *             @OA\Property(property="description", type="string", example="Updated description"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="image_url", type="string", example="https://example.com/newimage.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product updated successfully"
 *     )
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PATCH /products/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::productService()->updateProduct($id, $data));
});


/**
 * @OA\Delete(
 *     path="/products/{id}",
 *     tags={"Products"},
 *     summary="Delete a product by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=9)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product deleted successfully"
 *     )
 *     @OA\Response(response=500, description="Server error")
 */
Flight::route('DELETE /products/@id', function($id) {
    Flight::json(["deleted" => Flight::productService()->deleteProduct($id)]);
});


/**
 * @OA\Get(
 *     path="/products/all",
 *     tags={"Products"},
 *     summary="Get ALL products (no filtering)",
 *     @OA\Response(
 *         response=200,
 *         description="List of all products"
 *     )
 * )
 *     @OA\Response(response=500, description="Server error")
 */
Flight::route('GET /products/all', function() {
    Flight::json(Flight::productService()->getAllProducts());
});

?>
