<?php

$app->route(
    ['GET', 'PUT', 'POST', 'DELETE'], '/product[/{id}]',
     \App\Controllers\ProductController::class
    );
