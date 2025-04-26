<?php

namespace App\Database\QueryBuilder\Traits;

trait GroupByTrait
{
    protected array $groupBys = [];

    public function groupBy(string ...$columns): static
    {
        $this->groupBys = array_merge($this->groupBys, $columns);
        return $this;
    }

    protected function getGroupBy(): string
    {
        if (empty($this->groupBys)) {
            return '';
        }

        return "GROUP BY " . implode(", ", $this->groupBys);
    }
}
