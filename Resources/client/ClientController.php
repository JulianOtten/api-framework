<?php

namespace Resources\Client;

use App\Resources\AbstractResourceController;
use App\Resources\Interfaces\ResourceControllerInterface;

/**
 * Responsible for centralizing everything to do with the Client resource.
 */
class ClientController extends AbstractResourceController implements ResourceControllerInterface
{
    public function __construct()
    {
        $this->setInterface(new ClientInterface());
        // $this->setSeeder(new ClientSeeder());
        $this->setRoutes(new ClientRoutes());
    }
}
