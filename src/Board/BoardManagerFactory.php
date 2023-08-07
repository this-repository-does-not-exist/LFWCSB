<?php declare(strict_types=1);

namespace App\Board;

use App\BoardStorage\BoardStorage;
use App\BoardStorage\InMemoryBoardStorage;
use App\BoardStorage\JsonFileBoardStorage;

final class BoardManagerFactory
{
    public function create(): BoardManager
    {
        return new BoardManager(self::getBoardStorage('json'));
    }

    private static function getBoardStorage(string $name): BoardStorage
    {
        return match ($name) {
            'in-memory' => new InMemoryBoardStorage(),
            'json' => new JsonFileBoardStorage(__DIR__ . '/../../.board_data.json'),
            default => throw new BoardException(\sprintf('Unsupported board storage "%s"!', $name))
        };
    }
}
