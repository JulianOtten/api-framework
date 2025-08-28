<?php

namespace Resources\Service;

use App\Http\Method;
use App\Http\Request;
use App\Resources\Interfaces\ResourceRoutesInterface;
use App\Routing\Attributes\Path;
use App\Routing\Attributes\PathGroup;
use App\Routing\Exceptions\BadRequestException;
use App\Routing\Middleware\AuthenticatedMiddleware;
use App\Routing\Exceptions\NotFoundException;
use App\Services\ServiceFactory;
use Exception;
use Resources\Service\ServiceModal;

#[PathGroup('/services')]
class ServiceRoutes implements ResourceRoutesInterface
{
    #[Path('', Method::Get)]
    #[AuthenticatedMiddleware()]
    public function list()
    {
        $modal = new ServiceModal();
        $services = $modal->get();
        
        return $services;
    }
    
    #[Path('/{id}', Method::Get)]
    #[AuthenticatedMiddleware()]
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
        
    #[Path('/{id}/auth', Method::Get)]
    #[AuthenticatedMiddleware()]
    public function auth($id)
    {
        try {
            $service = ServiceFactory::fromServiceId($id);
            return [
                'url' => $service->getAuthorizationUrl(),
            ];
        } catch(Exception $e) {
            throw new BadRequestException($e->getMessage());
        }
    }

    #[Path('/{id}/callback', Method::Get)]
    public function callback($id)
    {
        $request = new Request();

        $params = $request->getQueryParams();
        
        if (!isset($params['code'])) {
            throw new BadRequestException('Parameter code is required');
        }

        try {
            $service = ServiceFactory::fromServiceId($id);
            $service->getAccessToken($params['code']);

            // redirect user back to the page they come from, which should be set when authenticating
        } catch(Exception $e) {
            throw new BadRequestException($e->getMessage());
        }
    }
}
