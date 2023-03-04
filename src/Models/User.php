<?php

declare(strict_types=1);

namespace Cli\Models;

readonly class User
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $gender,
    ) {}
}
