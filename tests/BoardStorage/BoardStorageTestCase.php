<?php declare(strict_types=1);

namespace Tests\BoardStorage;

use App\BoardStorage\BoardStorage;
use App\Game\Game;
use PHPUnit\Framework\TestCase;

abstract class BoardStorageTestCase extends TestCase
{
    abstract public function getBoardStorage(): BoardStorage;

    final public function testStoringAndRestoring(): void
    {
        $boardStorage = $this->getBoardStorage();

        $board = $boardStorage->restore();
        $board->addGame(new Game('Arg', 'Bra'));
        $board->addGame(new Game('Cro', 'Den'));
        $board->addGame(new Game('Eng', 'Fra'));

        $boardStorage->store($board);

        $restoredBoard = $boardStorage->restore();

        self::assertSame(
            \serialize($board),
            \serialize($restoredBoard),
        );
    }
}
