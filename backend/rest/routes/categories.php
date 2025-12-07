<?php
require_once __DIR__ . '/../../data/roles.php';

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Category management endpoints"
 * )
 */


/**
 * @OA\Get(
 *     path="/categories",
 *     tags={"Categories"},
 *     summary="Get all categories",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of all categories"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /categories', function() {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::categoryService()->getAllCategories());
});

/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     tags={"Categories"},
 *     summary="Get category by ID",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1),
 *         description="Category ID"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Single category object"
 *     ),
 *     @OA\Response(response=404, description="Category not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /categories/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::categoryService()->getCategoryById($id));
});


/**
 * @OA\Get(
 *     path="/categories/search/{query}",
 *     tags={"Categories"},
 *     summary="Search categories by name (case-insensitive)",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="query",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", example="Engine"),
 *         description="String used to filter category names"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Filtered list of categories"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /categories/search/@query', function($query) {
    
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::categoryService()->searchByName($query));
});

/**
 * @OA\Post(
 *     path="/categories",
 *     tags={"Categories"},
 *     summary="Create a new category",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"category_name", "description"},
 *             @OA\Property(property="category_name", type="string", example="Performance Parts"),
 *             @OA\Property(property="description", type="string", example="High-performance parts for racing vehicles")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Category created successfully"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('POST /categories', function() {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::categoryService()->insertCategory($data));
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     tags={"Categories"},
 *     summary="Update an existing category",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1),
 *         description="Category ID"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="category_name", type="string", example="Updated Category"),
 *             @OA\Property(property="description", type="string", example="Updated description for the category")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Category updated successfully"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('PUT /categories/@id', function($id) {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::categoryService()->updateCategory($id, $data));
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     tags={"Categories"},
 *     summary="Delete a category",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1),
 *         description="Category ID to delete"
 *     ),
 *     @OA\Response(response=200, description="Category deleted successfully"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('DELETE /categories/@id', function($id) {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::categoryService()->deleteCategory($id));
});

?>
