<?php

namespace App\Repositories;

use App\Interfaces\RouteRepositoryInterface;
use App\Models\Route;

class RouteRepository implements RouteRepositoryInterface
{
    public function getAllRoutes()
    {
        return Route::all();
    }

    public function createRoute(array $data)
    {
        return Route::create($data);
    }

    public function deleteRoute($id)
    {
        return Route::destroy($id);
    }
}