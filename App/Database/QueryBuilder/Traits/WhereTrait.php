<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\Helpers\Condition;
use App\Database\QueryBuilder\Interfaces\QueryInterface;

trait WhereTrait
{
    protected array $wheres = [];

    public function where(Condition ...$conditions): static
    {
        // dd($conditions);
        foreach ($conditions as $condition) {
            if (!$condition->getBind()) {
                continue;
            }

            $this->setBind('where', $condition->getValue());
        }

        $conditions = array_map(function (Condition $condition) {
            return $condition->get();
        }, $conditions);

        $this->wheres[] = sprintf("( %s )", implode(" OR ", $conditions));

        return $this;
    }

    public function and(Condition ...$condition): static
    {
        return $this->where(...$condition);
    }

    protected function getWheres(): string
    {
        if (empty($this->wheres)) {
            return "";
        }
        return "WHERE " . implode(" AND ", $this->wheres);
    }
}
