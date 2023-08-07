<?php declare(strict_types=1);

namespace App\Board;

use App\BoardStorage\BoardStorage;
use App\BoardStorage\InMemoryBoardStorage;

final class BoardManagerFactory
{
    public function create(): BoardManager
    {
        return new BoardManager(self::getBoardStorage('in-memory'));
    }

    private static function getBoardStorage(string $name): BoardStorage
    {
        return match ($name) {
            'in-memory' => new InMemoryBoardStorage(),
            default => throw new BoardException(\sprintf('Unsupported board storage "%s"!', $name))
        };
    }
}
