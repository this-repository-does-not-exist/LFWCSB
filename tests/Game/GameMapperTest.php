<?php declare(strict_types=1);

namespace Tests\Game;

use App\Game\Game;
use App\Game\GameMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Game\GameMapper
 */
final class GameMapperTest extends TestCase
{
    public function testMappingToArrayAndBack(): void
    {
        $game = new Game('Arg', 'Bra', 0, 3);

        $gameArray = GameMapper::toArray($game);

        self::assertCount(4, $gameArray);

        self::assertArrayHasKeyWithValue($gameArray, 'homeTeam', 'Arg');
        self::assertArrayHasKeyWithValue($gameArray, 'awayTeam', 'Bra');
        self::assertArrayHasKeyWithValue($gameArray, 'homeTeamScore', 0);
        self::assertArrayHasKeyWithValue($gameArray, 'awayTeamScore', 3);

        $gameMapped = GameMapper::formArray($gameArray);

        self::assertSame('Arg', $gameMapped->homeTeam());
        self::assertSame('Bra', $gameMapped->awayTeam());
        self::assertSame(0, $gameMapped->homeTeamScore());
        self::assertSame(3, $gameMapped->awayTeamScore());
    }

    public function testMappingFromArrayAndBack(): void
    {
        $game = [
            'homeTeam' => 'Aus',
            'awayTeam' => 'Bol',
            'homeTeamScore' => 8,
            'awayTeamScore' => 7,
        ];

        $gameMapped = GameMapper::formArray($game);

        self::assertSame('Aus', $gameMapped->homeTeam());
        self::assertSame('Bol', $gameMapped->awayTeam());
        self::assertSame(8, $gameMapped->homeTeamScore());
        self::assertSame(7, $gameMapped->awayTeamScore());

        $gameArray = GameMapper::toArray($gameMapped);

        self::assertCount(4, $gameArray);
        self::assertArrayHasKeyWithValue($gameArray, 'homeTeam', 'Aus');
        self::assertArrayHasKeyWithValue($gameArray, 'awayTeam', 'Bol');
        self::assertArrayHasKeyWithValue($gameArray, 'homeTeamScore', 8);
        self::assertArrayHasKeyWithValue($gameArray, 'awayTeamScore', 7);
    }

    /**
     * @param array<string, int|string> $gameArray
     */
    private function assertArrayHasKeyWithValue(array $gameArray, string $key, int|string $value): void
    {
        self::assertArrayHasKey($key, $gameArray);
        self::assertSame($value, $gameArray[$key]);
    }
}
