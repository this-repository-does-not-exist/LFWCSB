<?php declare(strict_types=1);

namespace App\Board;

use App\BoardStorage\BoardStorage;
use App\BoardStorage\InMemoryBoardStorage;
use App\BoardStorage\JsonFileBoardStorage;
use App\BoardStorage\RedisBoardStorage;

final class BoardManagerFactory
{
    public function create(): BoardManager
    {
        return new BoardManager(self::getBoardStorage('redis'));
    }

    private static function getBoardStorage(string $name): BoardStorage
    {
        $redis = new \Redis();
        $redis->connect('redis', 6379);

        return match ($name) {
            'in-memory' => new InMemoryBoardStorage(),
            'json' => new JsonFileBoardStorage(__DIR__ . '/../../.board_data.json'),
            'redis' => new RedisBoardStorage($redis, 'board'),
            default => throw new BoardException(\sprintf('Unsupported board storage "%s"!', $name))
        };
    }
}
