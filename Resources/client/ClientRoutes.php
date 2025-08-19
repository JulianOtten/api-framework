<?php

namespace Resources\Client;

use App\Http\Method;
use App\Resources\Interfaces\ResourceRoutesInterface;
use App\Routing\Attributes\Path;
use App\Routing\Attributes\PathGroup;

#[PathGroup('/clients')]
class ClientRoutes implements ResourceRoutesInterface
{
    #[Path('', Method::Get)]
    public function list()
    {
        echo 'look all the clients wow how nice';
    }

    #[Path('/{id}', Method::Get)]
    public function get($id)
    {
        echo $id;
    }
}
