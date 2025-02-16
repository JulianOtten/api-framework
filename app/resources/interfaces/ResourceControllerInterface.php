<?php

namespace App\Resources\Interfaces;

interface ResourceControllerInterface
{
    private ResourceInterface $interface;
    private ResourceSeederInterface $seeder;
    private ResourceControllerInterface $controller;
    private ResourceRoutesInterface $routes;
}
