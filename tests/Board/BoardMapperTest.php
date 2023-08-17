<?php declare(strict_types=1);

namespace Tests\Board;

use App\Board\Board;
use App\Board\BoardMapper;
use App\Game\Game;
use App\Game\GameMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Board\BoardMapper
 */
final class BoardMapperTest extends TestCase
{
    public function testMappingToArrayAndBack(): void
    {
        $games = [
            new Game('Arg', 'Bra', 0, 1),
            new Game('Cro', 'Den', 2, 3),
            new Game('Eng', 'Fra', 4, 5),
        ];

        $board = new Board();
        foreach ($games as $game) {
            $board->addGame($game);
        }

        $boardArray = BoardMapper::toArray($board);

        self::assertCount(3, $boardArray);
        self::assertArrayHasKeyWithValue($boardArray, 0, GameMapper::toArray($games[0]));
        self::assertArrayHasKeyWithValue($boardArray, 1, GameMapper::toArray($games[1]));
        self::assertArrayHasKeyWithValue($boardArray, 2, GameMapper::toArray($games[2]));

        $boardMapped = BoardMapper::fromArray($boardArray);

        self::assertSame(
            \serialize($games),
            \serialize($boardMapped->gamesOriginal()),
        );
    }

    public function testMappingFromArrayAndBack(): void
    {
        $games = [
            new Game('Arg', 'Bra', 0, 1),
            new Game('Cro', 'Den', 2, 3),
            new Game('Eng', 'Fra', 4, 5),
        ];

        $board = \array_map(
            static fn (Game $game): array => GameMapper::toArray($game),
            $games,
        );

        $boardMapped = BoardMapper::fromArray($board);

        self::assertSame(
            \serialize($games),
            \serialize($boardMapped->gamesOriginal()),
        );

        $boardArray = BoardMapper::toArray($boardMapped);

        self::assertCount(3, $boardArray);
        self::assertArrayHasKeyWithValue($boardArray, 0, GameMapper::toArray($games[0]));
        self::assertArrayHasKeyWithValue($boardArray, 1, GameMapper::toArray($games[1]));
        self::assertArrayHasKeyWithValue($boardArray, 2, GameMapper::toArray($games[2]));
    }

    /**
     * @param array<int, array{homeTeam: string, awayTeam: string, homeTeamScore: int, awayTeamScore: int}> $boardArray
     * @param array{homeTeam: string, awayTeam: string, homeTeamScore: int, awayTeamScore: int}             $value
     */
    private function assertArrayHasKeyWithValue(array $boardArray, int $key, array $value): void
    {
        self::assertArrayHasKey($key, $boardArray);
        self::assertSame($value, $boardArray[$key]);
    }
}
