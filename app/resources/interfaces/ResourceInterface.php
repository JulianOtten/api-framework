<?php

namespace App\Resources\Interfaces;

use App\Database\Orm\Column;

interface ResourceInterface
{

    #[Column(
        type: "varchar",
        length: 30,
        index: "PRIMARY",
    )]
    public string $id;

    #[Column(
        type: "datetime",
        nullable: true,
    )]
    public string $updatedAt;

    #[Column(
        type: "timestamp",
        default: "current_timestamp"
    )]
    public string $createdAt;
}
