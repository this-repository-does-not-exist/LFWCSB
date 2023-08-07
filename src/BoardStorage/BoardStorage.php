<?php declare(strict_types=1);

namespace App\BoardStorage;

use App\Board\Board;

interface BoardStorage
{
    public function store(Board $board): void;

    public function restore(): Board;
}
