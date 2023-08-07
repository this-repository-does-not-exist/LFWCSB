<?php declare(strict_types=1);

namespace Tests\BoardStorage;

use App\BoardStorage\InMemoryBoardStorage;
use App\Game\Game;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\BoardStorage\InMemoryBoardStorage
 */
final class InMemoryBoardStorageTest extends TestCase
{
    public function testStoringAndRestoring(): void
    {
        $boardStorage = new InMemoryBoardStorage();

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
