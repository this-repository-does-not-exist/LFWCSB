<?php declare(strict_types=1);

namespace App\Game;

final readonly class GameMapper
{
    /**
     * @return array{homeTeam: string, awayTeam: string, homeTeamScore: int, awayTeamScore: int}
     */
    public static function toArray(Game $game): array
    {
        return [
            'homeTeam' => $game->homeTeam(),
            'awayTeam' => $game->awayTeam(),
            'homeTeamScore' => $game->homeTeamScore(),
            'awayTeamScore' => $game->awayTeamScore(),
        ];
    }

    /**
     * @param array{homeTeam: string, awayTeam: string, homeTeamScore: int, awayTeamScore: int} $data
     */
    public static function fromArray(array $data): Game
    {
        return new Game(
            $data['homeTeam'],
            $data['awayTeam'],
            $data['homeTeamScore'],
            $data['awayTeamScore'],
        );
    }
}
