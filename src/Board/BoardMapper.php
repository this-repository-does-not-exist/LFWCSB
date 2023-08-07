<?php declare(strict_types=1);

namespace App\Board;

use App\Game\Game;
use App\Game\GameMapper;

final readonly class BoardMapper
{
    /**
     * @return array<int, array{homeTeam: string, awayTeam: string, homeTeamScore: int, awayTeamScore: int}>
     */
    public static function toArray(Board $board): array
    {
        return \array_map(
            static fn (Game $game): array => GameMapper::toArray($game),
            $board->gamesOriginal(),
        );
    }

    /**
     * @param array<int, array{homeTeam: string, awayTeam: string, homeTeamScore: int, awayTeamScore: int}> $gamesData
     */
    public static function formArray(array $gamesData): Board
    {
        $board = new Board();

        foreach ($gamesData as $gameData) {
            $board->addGame(GameMapper::formArray($gameData));
        }

        return $board;
    }
}
