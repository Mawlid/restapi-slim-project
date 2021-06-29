<?php

use App\Middleware\JsonResponseMiddleware;
use Slim\App;
use Slim\Twig;
require_once './vendor/autoload.php';

$container = new \Slim\Container;

$container['db'] = function () {
    return 'db';
};


$app = new \App\App($container);

$app->add(new JsonResponseMiddleware);

require_once 'routes.php';
