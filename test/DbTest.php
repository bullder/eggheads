<?php

declare(strict_types=1);

namespace test;

use Cli\Db;
use PHPUnit\Framework\TestCase;

class DbTest extends TestCase
{
    public final function testGet(): void
    {
        $db = new Db();
        $this->assertEquals(
            '[{"id":1,"name":"Mike","address":"Saratov","city":"Pushkina 3"},{"id":2,"name":"Vik","address":"Moscow","city":"Pomortseva 12"}]',
            json_encode($db->get())
        );
    }
}
