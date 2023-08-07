<?php declare(strict_types=1);

namespace Tests\Game;

use App\Game\Game;
use App\Game\GameException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Game\Game
 */
final class GameTest extends TestCase
{
    public function testCreatingGameWithTeamAgainstThemselves(): void
    {
        $this->expectException(GameException::class);
        $this->expectExceptionMessage('Cannot create a game with a team against themselves!');

        new Game('Arg', 'Arg');
    }

    public function testCreatingGame(): void
    {
        $game = new Game('Arg', 'Bra');

        self::assertSame('Arg', $game->homeTeam());
        self::assertSame('Bra', $game->awayTeam());
        self::assertSame(0, $game->homeTeamScore());
        self::assertSame(0, $game->awayTeamScore());
    }

    /**
     * @dataProvider provideUpdatingScoreCorrectlyCases
     */
    public function testUpdatingScoreCorrectly(
        int $homeTeamScoreBefore,
        int $awayTeamScoreBefore,
        int $homeTeamScoreAfter,
        int $awayTeamScoreAfter,
    ): void {
        $game = new Game('Arg', 'Bra', $homeTeamScoreBefore, $awayTeamScoreBefore);

        self::assertSame($homeTeamScoreBefore, $game->homeTeamScore());
        self::assertSame($awayTeamScoreBefore, $game->awayTeamScore());

        $game->updateScore($homeTeamScoreAfter, $awayTeamScoreAfter);

        self::assertSame($homeTeamScoreAfter, $game->homeTeamScore());
        self::assertSame($awayTeamScoreAfter, $game->awayTeamScore());
    }

    /**
     * @return iterable<array{int, int, int, int}>
     */
    public static function provideUpdatingScoreCorrectlyCases(): iterable
    {
        yield [0, 0, 1, 0];
        yield [0, 0, 0, 1];
        yield [0, 0, 4, 3];
        yield [5, 3, 5, 6];
        yield [1, 7, 8, 7];
    }

    /**
     * @dataProvider provideUpdatingScoreIncorrectlyCases
     */
    public function testUpdatingScoreIncorrectly(
        int $homeTeamScoreBefore,
        int $awayTeamScoreBefore,
        int $homeTeamScoreAfter,
        int $awayTeamScoreAfter,
        string $exceptionMessage,
    ): void {
        $game = new Game('Arg', 'Bra', $homeTeamScoreBefore, $awayTeamScoreBefore);

        self::assertSame($homeTeamScoreBefore, $game->homeTeamScore());
        self::assertSame($awayTeamScoreBefore, $game->awayTeamScore());

        $this->expectException(GameException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $game->updateScore($homeTeamScoreAfter, $awayTeamScoreAfter);
    }

    /**
     * @return iterable<array{int, int, int, int, string}>
     */
    public static function provideUpdatingScoreIncorrectlyCases(): iterable
    {
        yield [0, 0, 0, 0, 'Cannot update with the same scores!'];
        yield [1, 0, 0, 0, 'Cannot decrease score of home team!'];
        yield [5, 3, 1, 3, 'Cannot decrease score of home team!'];
        yield [0, 1, 0, 0, 'Cannot decrease score of away team!'];
        yield [1, 7, 2, 2, 'Cannot decrease score of away team!'];
        yield [3, 3, 2, 2, 'Cannot decrease score of home team!'];
    }
}
