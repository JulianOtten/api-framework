<?php

namespace Resources\Service;

use App\Http\Method;
use App\Resources\Interfaces\ResourceRoutesInterface;
use App\Routing\Attributes\Path;
use App\Routing\Attributes\PathGroup;
use App\Routing\Exceptions\NotFoundException;
use Resources\Service\ServiceModal;

#[PathGroup('/services')]
class ServiceRoutes implements ResourceRoutesInterface
{
    #[Path('', Method::Get)]
    public function list()
    {
        $modal = new ServiceModal();
        $services = $modal->get();

        return $services;
    }

    #[Path('/{id}', Method::Get)]
    public function get($id)
    {
        $modal = new ServiceModal();
        $service = $modal->get($id);

        if (empty($service)) {
            throw new NotFoundException([
                "message" => "Service with id $id was not found",
            ]);
        }

        return $service;
    }
}
