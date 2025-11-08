<?php

Flight::route('GET /categories', function() {
    Flight::json(Flight::categoryService()->getAllCategories());
});

Flight::route('GET /categories/search/@query', function($query) {
    Flight::json(Flight::categoryService()->searchByName($query));
});

?>