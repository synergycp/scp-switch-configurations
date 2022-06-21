<?php // Copyright 2014-present Zane Hooper. Do not use, modify, or distribute without written consent.

namespace App\Hub\Type;

use App\Http\RouteServiceProvider;
use Illuminate\Routing\Router;

/**
 * Routes regarding Switches.
 */
class TypeRoutesProvider extends RouteServiceProvider {
  protected function api(Router $router) {
    $router->resource('switch/type', TypeController::class);
  }
}
