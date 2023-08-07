<?php declare(strict_types=1);

namespace App\Board;

use App\Game\Game;

final class Board
{
    /** @var array<int, Game> */
    private array $games = [];

    public function addGame(Game $game): void
    {
        $this->assertTeamNotAlreadyOnBoard($game->homeTeam());
        $this->assertTeamNotAlreadyOnBoard($game->awayTeam());
        $this->games[] = $game;
    }

    public function getGame(string $homeTeam, string $awayTeam): Game
    {
        return $this->games[$this->getGameKey($homeTeam, $awayTeam)];
    }

    public function removeGame(string $homeTeam, string $awayTeam): void
    {
        unset($this->games[$this->getGameKey($homeTeam, $awayTeam)]);
        $this->games = \array_values($this->games);
    }

    /**
     * @return array<Game>
     */
    public function games(): array
    {
        $gamesWithIds = \array_map(
            static fn (int $id, Game $game): array => [$id, $game],
            \array_keys($this->games),
            \array_values($this->games),
        );

        \usort(
            $gamesWithIds,
            /**
             * @param array{int, Game} $game1
             * @param array{int, Game} $game2
             */
            static function (array $game1, array $game2): int {
                return $game1[1]->totalScore() === $game2[1]->totalScore()
                    ? $game2[0] <=> $game1[0]
                    : $game2[1]->totalScore() <=> $game1[1]->totalScore();
            },
        );

        return \array_map(
            static fn (array $game): Game => $game[1],
            $gamesWithIds,
        );
    }

    private function assertTeamNotAlreadyOnBoard(string $team): void
    {
        foreach ($this->games as $game) {
            if ($game->homeTeam() === $team || $game->awayTeam() === $team) {
                throw new BoardException(\sprintf('Team "%s" already on board!', $team));
            }
        }
    }

    private function getGameKey(string $homeTeam, string $awayTeam): int
    {
        foreach ($this->games as $key => $game) {
            if ($game->homeTeam() !== $homeTeam) {
                continue;
            }
            if ($game->awayTeam() !== $awayTeam) {
                continue;
            }

            return $key;
        }

        throw new BoardException('Game not found!');
    }
}
