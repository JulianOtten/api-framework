<?php

namespace Resources\Recipe;

use App\Database\Query;
use App\Database\QueryBuilder\QueryBuilderFactory;
use App\Http\Method;
use App\Resources\Interfaces\ResourceRoutesInterface;
use App\Routing\Attributes\Path;
use App\Routing\Attributes\PathGroup;

#[PathGroup('/recipes')]
class RecipeRoutes implements ResourceRoutesInterface
{
    #[Path('', Method::Get)]
    public function list()
    {
        
    }
}
