<?php

namespace Resources\Service;

use App\Resources\Interfaces\ResourceInterface;

class ServiceInterface implements ResourceInterface
{
    public string $id;

    public string $name;

    public ?string $icon;
}
