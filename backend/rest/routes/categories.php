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
 *     )
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
 *     )
 * )
 */
Flight::route('GET /categories/search/@query', function($query) {
    Flight::json(Flight::categoryService()->searchByName($query));
});

?>
