<?php

namespace Resources\Client;

use App\Database\Database;
use App\Database\QueryBuilder\QueryBuilderFactory;
use App\Resources\Interfaces\ResourceModalInterface;
use App\Util\Env;
use PDO;
use Resources\Client\ClientInterface;

use function App\Database\QueryBuilder\Functions\eq;

class ClientModal implements ResourceModalInterface
{

    public function __construct()
    {
        
    }

    public static function getByApiToken($token)
    {
        $builder = QueryBuilderFactory::fromConnection();

        $query = $builder->select()->from('client_auth_tokens')->where(eq('token', $token));

        $env = Env::getInstance();
        $db = new Database(
            $env->getVariable('MYSQL_HOST'),
            $env->getVariable('MYSQL_DATABASE'),
            $env->getVariable('MYSQL_USER'),
            $env->getVariable('MYSQL_PASSWORD'),
            // $env->getVariable('MYSQL_PORT'),
        );

        $stmt = $db->query($query);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $result = $stmt->fetch();

        return $result;
    }

}
