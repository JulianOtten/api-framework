<?php

namespace App\Resources\Interfaces;

interface ResourceSeederInterface
{
    public function create(): ResourceInterface;
}
