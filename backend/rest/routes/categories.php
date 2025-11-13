<?php

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
 *     @OA\Response(
 *         response=200,
 *         description="List of all categories"
 *     ),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /categories', function() {
    Flight::json(Flight::categoryService()->getAllCategories());
});


/**
 * @OA\Get(
 *     path="/categories/search/{query}",
 *     tags={"Categories"},
 *     summary="Search categories by name (case-insensitive)",
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
    Flight::json(Flight::categoryService()->searchByName($query));
});

/**
 * @OA\Post(
 *     path="/categories",
 *     tags={"Categories"},
 *     summary="Create a new category",
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
    $data = Flight::request()->data->getData();
    Flight::json(Flight::categoryService()->insertCategory($data));
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     tags={"Categories"},
 *     summary="Update an existing category",
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
    $data = Flight::request()->data->getData();
    Flight::json(Flight::categoryService()->updateCategory($id, $data));
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     tags={"Categories"},
 *     summary="Delete a category",
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
    Flight::json(Flight::categoryService()->deleteCategory($id));
});

?>
