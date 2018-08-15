<?php 

use Core\Router;

Router::Get('/404', App\Controllers\NotFound::class);
Router::Get('/', App\Controllers\DefaultController::class);
Router::Get('/info', App\Controllers\Info::class);
