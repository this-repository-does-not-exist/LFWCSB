<?php declare(strict_types=1);

namespace Tests\Game;

use App\Game\Game;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Game\Game
 */
final class GameTest extends TestCase
{
    public function testToHaveOneTest(): void
    {
        $game1 = new Game();
        $game2 = new Game();

        self::assertNotSame($game1, $game2);
    }
}
