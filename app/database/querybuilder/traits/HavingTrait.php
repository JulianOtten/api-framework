<?php

namespace App\Database\QueryBuilder\Traits;

use App\Database\Helpers\Condition;
use App\Database\QueryBuilder\Interfaces\QueryInterface;

trait HavingTrait
{
    protected array $havings = [];

    public function having(Condition ...$conditions): static
    {
        foreach ($conditions as $condition) {
            if (!$condition->getBind()) {
                continue;
            }

            $this->setBind('having', $condition->getValue());
        }

        $conditions = array_map(function (Condition $condition) {
            return $condition->get();
        }, $conditions);

        $this->havings[] = sprintf("( %s )", implode(" OR ", $conditions));

        return $this;
    }

    protected function getHavings(): string
    {
        if (empty($this->havings)) {
            return "";
        }
        return "WHERE " . implode(" AND ", $this->havings);
    }
}
