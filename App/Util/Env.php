<?php

namespace App\Util;

use App\Util\Classes\Singleton;
use Dotenv\Dotenv;

class Env
{
    use Singleton;

    public $dotenv = null;

    public function setup()
    {
        $this->dotenv = Dotenv::createImmutable(ROOT);
        $this->dotenv->load();

        $this->validate();
    }

    public function validate()
    {
        $this->dotenv->required([
            'MYSQL_ROOT_PASSWORD',
            'MYSQL_DATABASE',
            'MYSQL_USER',
            'MYSQL_PASSWORD',
        ])
        ->notEmpty();

        $this->dotenv->required('ENVIRONMENT')->allowedValues(['development', 'testing', 'production']);
    }

    public function getVariable(string $key, bool $required = true)
    {
        if ($required && !isset($_ENV[strtoupper($key)])) {
            throw new \Exception($key . " is not set as environment variable");
        }

        return $_ENV[strtoupper($key)] ?? null;
    }
}
