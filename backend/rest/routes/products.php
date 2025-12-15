<?php

require_once __DIR__ . '/../../data/roles.php';

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
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of products"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /products/public', function() {
    Flight::json(Flight::productService()->getAllProducts());
});

Flight::route('GET /products/in-stock', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::productService()->getProductsInStock());
});


Flight::route('GET /products/all', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::productService()->getAllProducts());
});

Flight::route('GET /products', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::productService()->search(""));
});

/**
 * @OA\Get(
 *     path="/products/{id}",
 *     tags={"Products"},
 *     summary="Get products by category",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Products for selected category returned"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /products/@id', function($id) {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::productService()->getByCategory($id));
});

/**
 * @OA\Get(
 *     path="/product/{id}",
 *     tags={"Products"},
 *     summary="Get a product by ID",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=4)),
 *     @OA\Response(response=200, description="Product returned")
 * )
 */
Flight::route('GET /product/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::productService()->getProductById($id));
});

/**
 * @OA\Patch(
 *     path="/products/{id}/stock",
 *     tags={"Products"},
 *     summary="Update product stock quantity",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=4)),
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"stock"}, @OA\Property(property="stock", type="integer", example=75))),
 *     @OA\Response(response=200, description="Stock updated")
 * )
 */
Flight::route('PATCH /products/@id/stock', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $payload = Flight::request()->data->getData();
    Flight::json(Flight::productService()->updateStock($id, (int)$payload['stock']));
});


/**
 * @OA\Post(
 *     path="/products",
 *     tags={"Products"},
 *     summary="Add a new product",
 *     security={{"ApiKey": {}}},
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
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /products', function() {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::productService()->addProduct($data));
});


/**
 * @OA\Patch(
 *     path="/products/{id}",
 *     tags={"Products"},
 *     summary="Update an existing product",
 *     security={{"ApiKey": {}}},
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
 *       @OA\Response(
 *         response=200,
 *         description="Product added successfully"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PATCH /products/@id', function($id) {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::productService()->updateProduct($id, $data));
});


/**
 * @OA\Delete(
 *     path="/products/{id}",
 *     tags={"Products"},
 *     summary="Delete a product by ID",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=9)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product deleted successfully"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /products/@id', function($id) {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(["deleted" => Flight::productService()->deleteProduct($id)]);
});

?>
