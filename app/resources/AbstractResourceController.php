<?php

namespace App\Resources;

use App\Resources\Interfaces\ResourceControllerInterface;
use App\Resources\Interfaces\ResourceInterface;
use App\Resources\Interfaces\ResourceRoutesInterface;
use App\Resources\Interfaces\ResourceSeederInterface;

abstract class AbstractResourceController
{
    private ?ResourceInterface $interface = null;
    private ?ResourceSeederInterface $seeder = null;
    private ?ResourceControllerInterface $controller = null;
    private ?ResourceRoutesInterface $routes = null;

    abstract public function __construct();

    public function setInterface(ResourceInterface $interface = null)
    {
        $this->interface = $interface;
    }

    public function setSeeder(ResourceSeederInterface $seeder = null)
    {
        $this->seeder = $seeder;
    }

    public function setController(ResourceControllerInterface $controller = null)
    {
        $this->controller = $controller;
    }

    public function setRoutes(ResourceRoutesInterface $routes = null)
    {
        $this->routes = $routes;
    }

    public function getInterface(): ?ResourceInterface
    {
        return $this->interface;
    }

    public function getSeeder(): ?ResourceSeederInterface
    {
        return $this->seeder;
    }

    public function getController(): ?ResourceControllerInterface
    {
        return $this->controller;
    }

    public function getRoutes(): ?ResourceRoutesInterface
    {
        return $this->routes;
    }
}
