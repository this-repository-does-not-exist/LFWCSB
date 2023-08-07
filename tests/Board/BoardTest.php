<?php declare(strict_types=1);

namespace Tests\Board;

use App\Board\Board;
use App\Board\BoardException;
use App\Game\Game;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Board\Board
 */
final class BoardTest extends TestCase
{
    public function testAddingAndGettingGame(): void
    {
        $board = new Board();
        self::assertCount(0, $board->gamesOriginal());
        self::assertCount(0, $board->gamesSorted());

        $board->addGame($game1 = new Game('Arg', 'Bra'));
        $board->addGame($game2 = new Game('Cro', 'Den'));
        $board->addGame($game3 = new Game('Eng', 'Fra'));
        self::assertCount(3, $board->gamesOriginal());
        self::assertCount(3, $board->gamesSorted());

        self::assertSame($game3, $board->getGame('Eng', 'Fra'));
        self::assertSame($game2, $board->getGame('Cro', 'Den'));
        self::assertSame($game1, $board->getGame('Arg', 'Bra'));
    }

    /**
     * @dataProvider provideAddingTheSameTeamTwiceCases
     */
    public function testAddingTheSameTeamTwice(string $homeTeam, string $awayTeam, string $exceptionMessage): void
    {
        $board = new Board();
        $board->addGame(new Game('Arg', 'Bra'));

        $this->expectException(BoardException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $board->addGame(new Game($homeTeam, $awayTeam));
    }

    /**
     * @return iterable<array{string, string, string}>
     */
    public static function provideAddingTheSameTeamTwiceCases(): iterable
    {
        yield ['Arg', 'Bra', 'Team "Arg" already on board!'];
        yield ['Arg', 'Eng', 'Team "Arg" already on board!'];
        yield ['Eng', 'Arg', 'Team "Arg" already on board!'];
        yield ['Bra', 'Eng', 'Team "Bra" already on board!'];
        yield ['Eng', 'Bra', 'Team "Bra" already on board!'];
    }

    /**
     * @dataProvider provideGettingGameThatIsNotOnBoardCases
     */
    public function testGettingGameThatIsNotOnBoard(string $homeTeam, string $awayTeam): void
    {
        $board = new Board();
        $board->addGame(new Game('Arg', 'Bra'));
        $board->addGame(new Game('Cro', 'Den'));
        $board->addGame(new Game('Eng', 'Fra'));

        $this->expectException(BoardException::class);
        $this->expectExceptionMessage('Game not found!');

        $board->getGame($homeTeam, $awayTeam);
    }

    /**
     * @return iterable<array{string, string}>
     */
    public static function provideGettingGameThatIsNotOnBoardCases(): iterable
    {
        yield 'swapped teams' => ['Bra', 'Arg'];
        yield 'teams from different games' => ['Arg', 'Den'];
        yield 'home team found, but not away team' => ['Arg', 'Uru'];
        yield 'away team found, but not home team' => ['Uru', 'Bra'];
        yield 'both teams not on board' => ['Uru', 'Ven'];
    }

    public function testRemovingGame(): void
    {
        $board = new Board();
        $board->addGame($game1 = new Game('Arg', 'Bra'));
        $board->addGame($game2 = new Game('Cro', 'Den'));
        $board->addGame($game3 = new Game('Eng', 'Fra'));

        $board->removeGame('Cro', 'Den');

        self::assertCount(2, $board->gamesOriginal());
        self::assertSame($game1, $board->getGame('Arg', 'Bra'));
        self::assertSame($game3, $board->getGame('Eng', 'Fra'));
    }

    public function testRemovingGameThatIsNotOnBoard(): void
    {
        $board = new Board();
        $board->addGame(new Game('Arg', 'Bra'));
        $board->addGame(new Game('Cro', 'Den'));

        $this->expectException(BoardException::class);
        $this->expectExceptionMessage('Game not found!');

        $board->removeGame('Eng', 'Fra');
    }

    public function testGamesWithDifferentScoresAreSorted(): void
    {
        $board = new Board();
        $board->addGame($game7 = new Game('Arg', 'Bra', 4, 3));
        $board->addGame($game1 = new Game('Cro', 'Den', 1, 0));
        $board->addGame($game0 = new Game('Eng', 'Fra', 0, 0));
        $board->addGame($game9 = new Game('Ger', 'Hun', 7, 2));
        $board->addGame($game3 = new Game('Ita', 'Jpn', 2, 1));

        self::assertSame(
            [$game7, $game1, $game0, $game9, $game3],
            $board->gamesOriginal(),
        );

        self::assertSame(
            [$game9, $game7, $game3, $game1, $game0],
            $board->gamesSorted(),
        );
    }

    public function testGamesWithSameScoreAreNotSwapped(): void
    {
        $board = new Board();
        $board->addGame($game2a = new Game('Arg', 'Bra', 2, 0));
        $board->addGame($game0 = new Game('Cro', 'Den', 0, 0));
        $board->addGame($game2b = new Game('Eng', 'Fra', 1, 1));
        $board->addGame($game7 = new Game('Ger', 'Hun', 4, 3));
        $board->addGame($game2c = new Game('Ita', 'Jpn', 1, 1));

        self::assertSame(
            [$game2a, $game0, $game2b, $game7, $game2c],
            $board->gamesOriginal(),
        );

        self::assertSame(
            [$game7, $game2c, $game2b, $game2a, $game0],
            $board->gamesSorted(),
        );
    }

    public function testGamesFromExerciseDescription(): void
    {
        $board = new Board();
        $board->addGame($gameMexicoCanada = new Game('Mexico', 'Canada', 0, 5));
        $board->addGame($gameSpainBrazil = new Game('Spain', 'Brazil', 10, 2));
        $board->addGame($gameGermanyFrance = new Game('Germany', 'France', 2, 2));
        $board->addGame($gameUruguayItaly = new Game('Uruguay', 'Italy', 6, 6));
        $board->addGame($gameArgentinaAustralia = new Game('Argentina', 'Australia', 3, 1));

        self::assertSame(
            [$gameUruguayItaly, $gameSpainBrazil, $gameMexicoCanada, $gameArgentinaAustralia, $gameGermanyFrance],
            $board->gamesSorted(),
        );
    }
}
