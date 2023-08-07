<?php declare(strict_types=1);

namespace App\Board;

use App\Game\Game;

interface BoardManagerInterface
{
    /**
     * @return array<Game>
     */
    public function getGames(): array;
}
