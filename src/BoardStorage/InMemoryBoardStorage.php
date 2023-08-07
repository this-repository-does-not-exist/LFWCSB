<?php declare(strict_types=1);

namespace App\BoardStorage;

use App\Board\Board;

final class InMemoryBoardStorage implements BoardStorage
{
    private Board $board;

    public function __construct()
    {
        $this->board = new Board();
    }

    public function store(Board $board): void
    {
        $this->board = $board;
    }

    public function restore(): Board
    {
        return $this->board;
    }
}
