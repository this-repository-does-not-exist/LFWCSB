<?php declare(strict_types=1);

namespace Tests\Console;

use App\Board\BoardManagerInterface;
use App\Console\SummaryCommand;
use App\Game\Game;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \App\Console\SummaryCommand
 */
final class SummaryCommandTest extends TestCase
{
    public function testShowingEmptyBoard(): void
    {
        $boardManager = $this->createMock(BoardManagerInterface::class);
        $boardManager->expects(self::once())
            ->method('getGames')
            ->willReturn([]);

        $command = new SummaryCommand($boardManager);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        self::assertSame(Command::SUCCESS, $commandTester->getStatusCode());
        self::assertSame(
            <<<'BOARD'
                +-------------------------------------+
                | Live Football World Cup Score Board |
                +-------------------------------------+
                | There are no live matches now!      |
                +-------------------------------------+
                BOARD,
            \trim($commandTester->getDisplay()),
        );
    }

    public function testShowingBoardWithGames(): void
    {
        $boardManager = $this->createMock(BoardManagerInterface::class);
        $boardManager->expects(self::once())
            ->method('getGames')
            ->willReturn([
                new Game('Arg', 'Bra', 0, 0),
                new Game('Cro', 'Den', 4, 3),
                new Game('Eng', 'Fra', 2, 2),
            ]);

        $command = new SummaryCommand($boardManager);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        self::assertSame(Command::SUCCESS, $commandTester->getStatusCode());
        self::assertSame(
            <<<'BOARD'
                +-------------------------------------+
                | Live Football World Cup Score Board |
                +-------------------------------------+
                | Arg 0 - Bra 0                       |
                | Cro 4 - Den 3                       |
                | Eng 2 - Fra 2                       |
                +-------------------------------------+
                BOARD,
            \trim($commandTester->getDisplay()),
        );
    }
}
