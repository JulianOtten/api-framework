<?php

namespace App\Database;

use App\Database\QueryBuilder\Interfaces\AbstractQueryInterface;
use App\Database\QueryBuilder\Paradigms\MySQL\QueryBuilder;
use App\Database\QueryBuilder\QueryBuilderFactory;
use PDO;
use PDOStatement;

class Database
{
    private PDO $pdo;

    public function __construct(
        string $host,
        string $dbname,
        string $username,
        string $password,
        int $port = 3306,
        string $charset = 'utf8mb4',
        array $options = []
    ) {
        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset};port={$port}";

        $defaults = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, $username, $password, $options + $defaults);
    }

    /**
     * Run a query, either from raw SQL or a SelectQueryInterface.
     *
     * @param string|SelectQueryInterface $query
     * @param array $binds
     * @return PDOStatement
     */
    public function query(string|AbstractQueryInterface $query, array $binds = []): PDOStatement
    {
        if ($query instanceof AbstractQueryInterface) {
            $sql   = $query->build();
            $binds = $query->getBinds();
        } else {
            $sql = $query;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($binds);

        return $stmt;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public static function builder(): QueryBuilder
    {
        return QueryBuilderFactory::fromConnection();
    }
}
