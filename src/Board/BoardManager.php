<?php declare(strict_types=1);

namespace App\Board;

use App\BoardStorage\BoardStorage;
use App\Game\Game;

final readonly class BoardManager implements BoardManagerInterface
{
    public function __construct(
        private BoardStorage $boardStorage,
    ) {}

    public function startGame(string $homeTeam, string $awayTeam): void
    {
        $board = $this->boardStorage->restore();
        $board->addGame(new Game($homeTeam, $awayTeam));
        $this->boardStorage->store($board);
    }

    public function getGames(): array
    {
        return $this->boardStorage->restore()->games();
    }
}
