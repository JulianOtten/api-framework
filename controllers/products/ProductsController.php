<?php

namespace Controllers\Products;

use App\Routing\AbstractRouteController;
use App\Routing\Attributes\Path;

#[Path('/products')]
class ProductsController extends AbstractRouteController
{
    public function get()
    {
    }
}
