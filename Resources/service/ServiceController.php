<?php

namespace Resources\Service;

use App\Resources\AbstractResourceController;
use App\Resources\Interfaces\ResourceControllerInterface;

/**
 * Responsible for centralizing everything to do with the Service resource.
 */
class ServiceController extends AbstractResourceController implements ResourceControllerInterface
{
    public function __construct()
    {
        $this->setInterface(new ServiceInterface());
        // $this->setSeeder(new ServiceSeeder());
        $this->setRoutes(new ServiceRoutes());
    }
}
