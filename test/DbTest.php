<?php

declare(strict_types=1);

namespace test;

use Cli\Db;
use PHPUnit\Framework\TestCase;

class DbTest extends TestCase
{
    private Db $db;

    public function setUp(): void
    {
        parent::setUp();

        $this->db = new Db();
    }

    /**
     * @dataProvider dataProvider
     */
    public final function testGet(int $cat, int $count, string $data): void
    {
        $r = $this->db->get($cat);

        $this->assertCount($count, $r);
        $this->assertEquals($data, json_encode($r));
    }

    public final static function dataProvider(): array
    {
        return [
            'test 1 cat' => [1, 2, '[{"id":100,"user":{"id":1,"name":"Mike","gender":"Male"}},{"id":101,"user":{"id":2,"name":"Vik","gender":"Female"}}]'],
            'test 2 cat' => [2, 1, '[{"id":103,"user":{"id":1,"name":"Mike","gender":"Male"}}]'],
        ];
    }
}
