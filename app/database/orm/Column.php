<?php

namespace App\Database\Orm;

#[\Attribute]
class Column
{
    public function __construct(
        public $type,
        public $length = null,
        public $index = null,
        public $autoIncrement = false,
        public $default = null,
        public $attributes = [],
        public $nullable = false,
    ){}
    
}
