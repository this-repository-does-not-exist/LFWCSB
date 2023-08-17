<?php declare(strict_types=1);

namespace App\BoardStorage;

use App\Board\Board;
use App\Board\BoardMapper;

final readonly class JsonFileBoardStorage implements BoardStorage
{
    public function __construct(
        private string $path,
    ) {}

    public function store(Board $board): void
    {
        \file_put_contents(
            $this->path,
            \json_encode(
                BoardMapper::toArray($board),
                \JSON_PRETTY_PRINT,
            ),
        );
    }

    public function restore(): Board
    {
        if (!\file_exists($this->path)) {
            return new Board();
        }

        $content = \file_get_contents($this->path);
        \assert(\is_string($content));

        /** @var array<int, array{homeTeam: string, awayTeam: string, homeTeamScore: int, awayTeamScore: int}> $data */
        $data = \json_decode($content, true);

        return BoardMapper::fromArray($data);
    }
}
