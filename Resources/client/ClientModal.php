<?php

namespace Resources\Client;

use App\App;
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
        if (empty($token)) {
            return false;
        }

        $builder = Database::builder();

        $query = $builder->select()->from('client_auth_tokens')->where(eq('token', $token));

        $stmt = App::$db->query($query);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $result = $stmt->fetch();

        return $result;
    }

}
