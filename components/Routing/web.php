<?php

use app\components\Routing\Route;
use app\components\Routing\RouteReflection;
use app\controllers\UsersController;

RouteReflection::handle()->setControllers([
    UsersController::class,
]);
RouteReflection::handle()->generate();

Route::apiResource('users', UsersController::class);