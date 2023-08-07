<?php declare(strict_types=1);

namespace App\Board;

use App\Game\Game;

interface BoardManagerInterface
{
    public function startGame(string $homeTeam, string $awayTeam): void;

    /**
     * @return array<Game>
     */
    public function getGames(): array;
}
