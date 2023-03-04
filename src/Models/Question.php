<?php

declare(strict_types=1);

namespace Cli\Models;

readonly class Question
{
    public function __construct(
        public int    $id,
        public User $user,
    ) {}
}
