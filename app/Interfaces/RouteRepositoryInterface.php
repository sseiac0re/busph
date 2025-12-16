<?php

namespace App\Interfaces;

interface RouteRepositoryInterface
{
    public function getAllRoutes();
    public function createRoute(array $data);
    public function deleteRoute($id);
}