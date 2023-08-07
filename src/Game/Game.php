<?php declare(strict_types=1);

namespace App\Game;

final class Game
{
    public function __construct(
        private readonly string $homeTeam,
        private readonly string $awayTeam,
        private int $homeTeamScore = 0,
        private int $awayTeamScore = 0,
    ) {
        if ($homeTeam === $awayTeam) {
            throw new GameException('Cannot create a game with a team against themselves!');
        }
    }

    public function updateScore(int $homeTeamScore, int $awayTeamScore): void
    {
        if ($homeTeamScore < $this->homeTeamScore) {
            throw new GameException('Cannot decrease score of home team!');
        }

        if ($awayTeamScore < $this->awayTeamScore) {
            throw new GameException('Cannot decrease score of away team!');
        }

        if ($homeTeamScore + $awayTeamScore === $this->totalScore()) {
            throw new GameException('Cannot update with the same scores!');
        }

        $this->homeTeamScore = $homeTeamScore;
        $this->awayTeamScore = $awayTeamScore;
    }

    public function homeTeam(): string
    {
        return $this->homeTeam;
    }

    public function awayTeam(): string
    {
        return $this->awayTeam;
    }

    public function homeTeamScore(): int
    {
        return $this->homeTeamScore;
    }

    public function awayTeamScore(): int
    {
        return $this->awayTeamScore;
    }

    public function totalScore(): int
    {
        return $this->homeTeamScore + $this->awayTeamScore;
    }
}
