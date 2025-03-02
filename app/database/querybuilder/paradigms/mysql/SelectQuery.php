<?php

namespace App\Database\QueryBuilder\Paradigms\MySQL;

use App\Database\QueryBuilder\Abstract\AbstractQuery;
use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use App\Database\QueryBuilder\Traits\JoinTrait;
use App\Database\QueryBuilder\Traits\LimitTrait;
use App\Database\QueryBuilder\Traits\WhereTrait;

class SelectQuery extends AbstractQuery implements SelectQueryInterface
{
    use WhereTrait;
    use LimitTrait;
    use JoinTrait;

    protected string $table;
    protected array $columns = null;

    public function __construct(string|SelectQueryInterface ...$columns)
    {
        if(count($columns) == 0) {
            $columns = ['*'];
        }

        $this->columns = $columns;
    }

    public function columns(string|SelectQueryInterface ...$columns): SelectQueryInterface
    {
        $this->columns = $columns;
        return $this;
    }

    public function from(string $table): SelectQueryInterface
    {
        $this->table = $table;

        return $this;
    }

    public function build(): string
    {
        return "";
    }
}
