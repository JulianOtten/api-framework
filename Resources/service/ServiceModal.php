<?php

namespace Resources\Service;

use App\Database\Database;
use App\Database\QueryBuilder\QueryBuilderFactory;
use App\Resources\Interfaces\ResourceModalInterface;
use App\Util\Env;
use PDO;

use function App\Database\QueryBuilder\Functions\eq;

class ServiceModal implements ResourceModalInterface
{

    public function __construct()
    {
        
    }

    public function get($id = null)
    {
        $query = QueryBuilderFactory::fromConnection()
            ->select()
            ->from('services');

        if (!is_null($id)) {
            $query->where(eq('id', $id));
        }

        $env = Env::getInstance();
        $db = new Database(
            $env->getVariable('MYSQL_HOST'),
            $env->getVariable('MYSQL_DATABASE'),
            $env->getVariable('MYSQL_USER'),
            $env->getVariable('MYSQL_PASSWORD'),
            // $env->getVariable('MYSQL_PORT'),
        );

        return $db->query($query)->fetchAll(PDO::FETCH_CLASS, ServiceInterface::class);
    }

}
