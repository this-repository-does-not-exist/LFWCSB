<?php declare(strict_types=1);

namespace Tests\BoardStorage;

use App\BoardStorage\BoardStorage;
use App\BoardStorage\RedisBoardStorage;

/**
 * @covers \App\BoardStorage\RedisBoardStorage
 */
final class RedisBoardStorageTest extends BoardStorageTestCase
{
    public function getBoardStorage(): BoardStorage
    {
        $redis = new \Redis();
        $redis->connect('redis', 6379);

        $redis->unlink('board_test');

        return new RedisBoardStorage($redis, 'board_test');
    }
}
