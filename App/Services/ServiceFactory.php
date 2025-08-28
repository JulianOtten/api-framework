<?php

namespace App\Services;

use App\App;
use App\Database\Database;
use App\Services\Interfaces\ServiceInterface;
use PDO;

use function App\Database\QueryBuilder\Functions\eq;

class ServiceFactory
{
    /**
     * Create a service instance by its ID.
     *
     * @param string $serviceId
     * @return ServiceInterface
     * @throws \InvalidArgumentException
     */
    public static function fromServiceId(string $serviceId): ServiceInterface
    {
        $query = Database::builder()->select('service_class')->from('services')->where(eq('id', $serviceId));

        $serviceClass = App::$db->query($query)->fetch(PDO::FETCH_COLUMN);

        $serviceClass = "App\\Services\\Providers\\" . $serviceClass;

        // dd($serviceClass);
        if (!$serviceClass || !class_exists($serviceClass)) {
            throw new \InvalidArgumentException("Service class not found for ID: $serviceId");
        }

        $service = new $serviceClass();

        if (!$service instanceof \App\Services\Interfaces\ServiceInterface) {
            throw new \InvalidArgumentException("Service does not implement ServiceInterface");
        }

        return $service;
    }
}