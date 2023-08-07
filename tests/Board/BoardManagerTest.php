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

    public function testStartingAndFinishingGame(): void
    {
        self::assertCount(0, $this->boardManager->getGames());

        $this->boardManager->startGame('Arg', 'Bra');
        self::assertSame(
            \serialize([new Game('Arg', 'Bra')]),
            \serialize($this->boardManager->getGames()),
        );

        $this->boardManager->startGame('Cro', 'Den');
        self::assertSame(
            \serialize([new Game('Cro', 'Den'), new Game('Arg', 'Bra')]),
            \serialize($this->boardManager->getGames()),
        );

        $this->boardManager->startGame('Eng', 'Fra');
        self::assertSame(
            \serialize([new Game('Eng', 'Fra'), new Game('Cro', 'Den'), new Game('Arg', 'Bra')]),
            \serialize($this->boardManager->getGames()),
        );

        $this->boardManager->finishGame('Cro', 'Den');
        self::assertSame(
            \serialize([new Game('Eng', 'Fra'), new Game('Arg', 'Bra')]),
            \serialize($this->boardManager->getGames()),
        );
    }

    public function testGameFullCycle(): void
    {
        self::assertCount(0, $this->boardManager->getGames());

        $this->boardManager->startGame('Arg', 'Bra');
        self::assertSame(
            \serialize([new Game('Arg', 'Bra', 0, 0)]),
            \serialize($this->boardManager->getGames()),
        );

        $this->boardManager->updateScore('Arg', 'Bra', 2, 1);
        self::assertSame(
            \serialize([new Game('Arg', 'Bra', 2, 1)]),
            \serialize($this->boardManager->getGames()),
        );

        $this->boardManager->updateScore('Arg', 'Bra', 2, 3);
        self::assertSame(
            \serialize([new Game('Arg', 'Bra', 2, 3)]),
            \serialize($this->boardManager->getGames()),
        );

        $this->boardManager->updateScore('Arg', 'Bra', 4, 3);
        self::assertSame(
            \serialize([new Game('Arg', 'Bra', 4, 3)]),
            \serialize($this->boardManager->getGames()),
        );

        $this->boardManager->updateScore('Arg', 'Bra', 5, 5);
        self::assertSame(
            \serialize([new Game('Arg', 'Bra', 5, 5)]),
            \serialize($this->boardManager->getGames()),
        );

        $this->boardManager->finishGame('Arg', 'Bra');
        self::assertCount(0, $this->boardManager->getGames());
    }
}
