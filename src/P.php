<?php

declare(strict_types=1);

namespace Cli;

class P
{
    public function __construct(
        readonly int    $id,
        readonly string $name,
        readonly string $address,
        readonly string $city,
    ) {}
}
