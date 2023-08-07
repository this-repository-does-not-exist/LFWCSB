<?php declare(strict_types=1);

namespace Tests\Board;

use App\Board\Board;
use App\Board\BoardManager;
use App\BoardStorage\BoardStorage;
use App\BoardStorage\InMemoryBoardStorage;
use App\Game\Game;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Board\BoardManager
 */
final class BoardManagerTest extends TestCase
{
    private BoardStorage $boardStorage;
    private BoardManager $boardManager;

    protected function setUp(): void
    {
        $this->boardStorage = new InMemoryBoardStorage();
        $this->boardManager = new BoardManager($this->boardStorage);
    }

    public function testGettingGames(): void
    {
        $games = [
            new Game('Arg', 'Bra'),
            new Game('Cro', 'Den'),
            new Game('Eng', 'Fra'),
        ];

        $board = new Board();
        foreach ($games as $game) {
            $board->addGame($game);
        }

        $this->boardStorage->store($board);

        self::assertSame(
            \serialize(\array_reverse($games)),
            \serialize($this->boardManager->getGames()),
        );
    }
}
