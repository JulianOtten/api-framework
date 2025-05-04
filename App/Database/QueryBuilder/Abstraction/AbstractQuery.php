<?php

namespace App\Database\QueryBuilder\Abstraction;

use App\Database\QueryBuilder\Interfaces\AbstractQueryInterface;
use App\Database\QueryBuilder\Interfaces\SelectQueryInterface;
use InvalidArgumentException;
use Stringable;

abstract class AbstractQuery implements Stringable, AbstractQueryInterface
{
    protected $query = null;

    /**
     * Can be overwritten to join query segments with additional information.
     * Leave blank for query with 0 formatting.
     *
     * @example string "\\n" gives queries newlines between segments, making them more readable
     *
     * @var string
     */
    protected $implodeValue = "";

    /**
     * Variable used for checking if a query is valid
     * if any input into the query builder does not passt the santize method
     * it will set this property to false, and return en empty query
     *
     * @var boolean
     */
    protected $valid = true;

    /**
     * All types of binds we have in a query
     * this is ordered by in which order they appear in the query
     * that way the user can enter the logic in which ever way they want, but the values
     * will always line up correctly
     *
     * @var array
     */
    protected $binds = [
        "join" => [],
        "where" => [],
        "having" => [],
        "limit" => [],
    ];

    abstract public function build(): string;

    /**
     * Validate that unbindable input into the query builder matches a character whitelist
     * if for some reason this does not match, invalidate the query and return
     * an empty query string, for security reasons
     *
     * @param string $input
     * @return string
     */
    protected function sanitize(string|SelectQueryInterface $input): string
    {
        if (gettype($input) !== "string") {
            return $input;
        }

        /**
         * Allow the following:
         * - dashes (for column names)
         * a-zA-Z (all letters, in lower and upper case)
         * 0-9 all numbers
         * _ all underscores
         * . all dots
         * () all perenthesis (for sql functions like SUM and COUNT)
         * \s all spaces and white spaces (for column and table aliasses)
         */
        if (!preg_match('/^[-a-zA-Z0-9_.()\s*]+$/', $input)) {
            $this->valid = false;
            // throw new InvalidArgumentException("Invalid SQL identifier: $input");
        }

        // double dash is mysql's way of indicating comments, we do NOT want to allow them
        if (str_contains($input, '--')) {
            $this->valid = false;
        }

        return $input;
    }

    protected function setBind($type, $value)
    {
        if (!isset($this->binds[$type])) {
            throw new InvalidArgumentException("$type is not a valid bind option");
        }

        if (is_array($value)) {
            foreach ($value as $val) {
                $this->binds[$type][] = $val;
            }
            return;
        }

        $this->binds[$type][] = $value;
    }

    public function getBinds(): array
    {
        return array_reduce($this->binds, function ($acc, $arr) {
            return [...$acc, ...$arr];
        }, []);
    }

    protected function setSubQueryBinds(SelectQueryInterface $query): void
    {
        $binds = $query->getRawBinds();

        foreach ($binds as $type => $values) {
            foreach ($values as $value) {
                $this->setBind($type, $value);
            }
        }
    }

    public function getRawBinds(): array
    {
        return $this->binds;
    }

    public function reset(): static
    {
        $this->query = null;
        $this->binds = array_map(function ($el) {
            return [];
        }, $this->binds);

        return $this;
    }

    protected function getImplodeValue(): string
    {
        return $this->implodeValue;
    }

    public function __toString(): string
    {
        return $this->build();
    }

    public function dump(): string
    {
        $query = $this->build();

        // $query = str_replace(
        //     array_fill(0, count($this->getBinds()), '?'),
        //     array_map(fn($bind) => sprintf("\"%s\"", $bind), $this->getBinds()),
        //     $query
        // );

        $query = preg_replace_callback(
            '/\?/', // Match any "?" placeholder
            function ($matches) {
                // Get the next bind value from the list
                static $bindIndex = 0; // Keep track of which bind we are replacing
                $bindValue = $this->getBinds()[$bindIndex];

                // Increment the bind index for the next replacement
                $bindIndex++;

                if (is_numeric($bindValue)) {
                    return $bindValue;
                }

                // Return the bind value wrapped in quotes (escaping is done here)
                return sprintf("\"%s\"", addslashes($bindValue));
            },
            $query
        );

        dd($query);
    }
}
