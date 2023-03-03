<?php

declare(strict_types=1);

namespace Cli;

class Hello
{
    public static function hello(): string
    {
        $db = new Db();

        return json_encode($db->get());
    }
}
