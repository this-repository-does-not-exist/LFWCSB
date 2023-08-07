<?php declare(strict_types=1);

namespace Tests\Console;

use App\Board\BoardException;
use App\Board\BoardManagerInterface;
use App\Console\FinishGameCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \App\Console\FinishGameCommand
 */
final class FinishGameCommandTest extends TestCase
{
    public function testFinishingGameUnsuccessfully(): void
    {
        $boardManager = $this->createMock(BoardManagerInterface::class);
        $boardManager->expects(self::once())
            ->method('finishGame')
            ->willThrowException(new BoardException('Hi, I am error message!'));

        $command = new FinishGameCommand($boardManager);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'homeTeam' => 'Arg',
            'awayTeam' => 'Bra',
        ]);

        self::assertSame(Command::FAILURE, $commandTester->getStatusCode());
        self::assertSame('Hi, I am error message!', \trim($commandTester->getDisplay()));
    }

    public function testFinishingGameSuccessfully(): void
    {
        $boardManager = $this->createMock(BoardManagerInterface::class);
        $boardManager->expects(self::once())
            ->method('finishGame')
            ->with('Arg', 'Bra');

        $command = new FinishGameCommand($boardManager);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'homeTeam' => 'Arg',
            'awayTeam' => 'Bra',
        ]);

        self::assertSame(Command::SUCCESS, $commandTester->getStatusCode());
        self::assertSame('Game finished.', \trim($commandTester->getDisplay()));
    }
}
