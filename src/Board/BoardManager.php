<?php declare(strict_types=1);

namespace App\Board;

use App\BoardStorage\BoardStorage;

final readonly class BoardManager implements BoardManagerInterface
{
    public function __construct(
        private BoardStorage $boardStorage,
    ) {}

    public function getGames(): array
    {
        return $this->boardStorage->restore()->games();
    }
}
