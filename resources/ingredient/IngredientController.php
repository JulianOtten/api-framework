<?php

namespace Resources\Ingredient;

use App\Resources\AbstractResourceController;
use App\Resources\Interfaces\ResourceControllerInterface;

/**
 * Responsible for centralizing everything to do with the Ingredient resource.
 */
class IngredientController extends AbstractResourceController implements ResourceControllerInterface
{
    public function __construct()
    {
        $this->setInterface(new IngredientInterface());
        $this->setSeeder(new IngredientSeeder());
        $this->setRoutes(new IngredientRoutes());
    }
}
