<?php

namespace Resources\Service;

use App\App;
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
        $query = Database::builder()
            ->select()
            ->from('services');

        if (!is_null($id)) {
            $query->where(eq('id', $id));
        }

        return App::$db->query($query)->fetchAll(PDO::FETCH_CLASS, ServiceInterface::class);
    }

}
