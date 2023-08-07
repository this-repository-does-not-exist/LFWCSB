<?php declare(strict_types=1);

namespace Tests\Console;

use App\Board\BoardException;
use App\Board\BoardManagerInterface;
use App\Console\StartGameCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \App\Console\StartGameCommand
 */
final class StartGameCommandTest extends TestCase
{
    public function testStartingGameUnsuccessfully(): void
    {
        $boardManager = $this->createMock(BoardManagerInterface::class);
        $boardManager->expects(self::once())
            ->method('startGame')
            ->willThrowException(new BoardException('Hi, I am error message!'));

        $command = new StartGameCommand($boardManager);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'homeTeam' => 'Arg',
            'awayTeam' => 'Bra',
        ]);

        self::assertSame(Command::FAILURE, $commandTester->getStatusCode());
        self::assertSame('Hi, I am error message!', \trim($commandTester->getDisplay()));
    }

    public function testStartingGameSuccessfully(): void
    {
        $boardManager = $this->createMock(BoardManagerInterface::class);
        $boardManager->expects(self::once())
            ->method('startGame')
            ->with('Arg', 'Bra');

        $command = new StartGameCommand($boardManager);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'homeTeam' => 'Arg',
            'awayTeam' => 'Bra',
        ]);

        self::assertSame(Command::SUCCESS, $commandTester->getStatusCode());
        self::assertSame('Game started.', \trim($commandTester->getDisplay()));
    }
}
