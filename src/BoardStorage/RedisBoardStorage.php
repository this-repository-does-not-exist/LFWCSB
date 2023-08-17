<?php declare(strict_types=1);

namespace App\BoardStorage;

use App\Board\Board;
use App\Board\BoardMapper;
use App\Game\Game;
use App\Game\GameMapper;

final readonly class RedisBoardStorage implements BoardStorage
{
    public function __construct(
        private \Redis $redis,
        private string $cacheKey,
    ) {}

    public function store(Board $board): void
    {
        $data = \array_map(
            static fn (Game $game): array => GameMapper::toArray($game),
            $board->gamesOriginal(),
        );

        $this->redis->set($this->cacheKey, \json_encode($data));
    }

    public function restore(): Board
    {
        if ($this->redis->exists($this->cacheKey) === 0) {
            return new Board();
        }

        $cachedValue = $this->redis->get($this->cacheKey);
        \assert(\is_string($cachedValue));

        /** @var array<int, array{homeTeam: string, awayTeam: string, homeTeamScore: int, awayTeamScore: int}> $data */
        $data = \json_decode($cachedValue, true);

        return BoardMapper::fromArray($data);
    }
}
