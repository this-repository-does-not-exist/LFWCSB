<?php declare(strict_types=1);

namespace Tests\Console;

use App\Board\BoardException;
use App\Board\BoardManagerInterface;
use App\Console\UpdateScoreCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \App\Console\UpdateScoreCommand
 */
final class UpdateScoreCommandTest extends TestCase
{
    public function testUpdatingWithHomeTeamScoreNotInteger(): void
    {
        $command = new UpdateScoreCommand($this->createMock(BoardManagerInterface::class));
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'homeTeam' => 'Arg',
            'awayTeam' => 'Bra',
            'homeTeamScore' => 'five',
            'awayTeamScore' => '1',
        ]);

        self::assertSame(Command::FAILURE, $commandTester->getStatusCode());
        self::assertSame('Home team score must be integer!', \trim($commandTester->getDisplay()));
    }

    public function testUpdatingWithAwayTeamScoreNotInteger(): void
    {
        $command = new UpdateScoreCommand($this->createMock(BoardManagerInterface::class));
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'homeTeam' => 'Arg',
            'awayTeam' => 'Bra',
            'homeTeamScore' => '5',
            'awayTeamScore' => 'one',
        ]);

        self::assertSame(Command::FAILURE, $commandTester->getStatusCode());
        self::assertSame('Away team score must be integer!', \trim($commandTester->getDisplay()));
    }

    public function testUpdatingUnsuccessfully(): void
    {
        $boardManager = $this->createMock(BoardManagerInterface::class);
        $boardManager->expects(self::once())
            ->method('updateScore')
            ->willThrowException(new BoardException('Hi, I am error message!'));

        $command = new UpdateScoreCommand($boardManager);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'homeTeam' => 'Arg',
            'awayTeam' => 'Bra',
            'homeTeamScore' => '5',
            'awayTeamScore' => '1',
        ]);

        self::assertSame(Command::FAILURE, $commandTester->getStatusCode());
        self::assertSame('Hi, I am error message!', \trim($commandTester->getDisplay()));
    }

    public function testUpdatingSuccessfully(): void
    {
        $boardManager = $this->createMock(BoardManagerInterface::class);
        $boardManager->expects(self::once())
            ->method('updateScore')
            ->with('Arg', 'Bra', 5, 1);

        $command = new UpdateScoreCommand($boardManager);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'homeTeam' => 'Arg',
            'awayTeam' => 'Bra',
            'homeTeamScore' => '5',
            'awayTeamScore' => '1',
        ]);

        self::assertSame(Command::SUCCESS, $commandTester->getStatusCode());
        self::assertSame('Score updated.', \trim($commandTester->getDisplay()));
    }
}
