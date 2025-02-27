<?php

namespace Resources\Recipe;

use App\Resources\AbstractResourceController;
use App\Resources\Interfaces\ResourceControllerInterface;

/**
 * Responsible for centralizing everything to do with the Ingredient resource.
 */
class RecipeController extends AbstractResourceController implements ResourceControllerInterface
{
    public function __construct()
    {
        $this->setInterface(new RecipeInterface());
        $this->setSeeder(new RecipeSeeder());
        $this->setRoutes(new RecipeRoutes());
    }
}
