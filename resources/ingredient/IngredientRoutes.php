<?php

namespace Resources\Ingredient;

use App\Http\Method;
use App\Resources\Interfaces\ResourceRoutesInterface;
use App\Routing\Attributes\Path;
use App\Routing\Attributes\PathGroup;

#[PathGroup('/ingredients')]
class IngredientRoutes implements ResourceRoutesInterface
{
    #[Path('', Method::Get)]
    public function list()
    {
        echo "HIT";
    }

    #[Path('/{id}', Method::Get)]
    public function get($id)
    {
        echo $id;
    }

    #[Path('', Method::Post)]
    public function create()
    {
        echo "nice post request";
    }
}
