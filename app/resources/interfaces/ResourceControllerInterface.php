<?php

namespace App\Resources\Interfaces;

interface ResourceControllerInterface
{
    public ?ResourceInterface $interface { get; }
    public ?ResourceSeederInterface $seeder { get; }
    public ?ResourceRoutesInterface $routes { get; }
}
