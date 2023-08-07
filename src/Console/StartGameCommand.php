<?php declare(strict_types=1);

namespace App\Console;

use App\Board\BoardException;
use App\Board\BoardManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class StartGameCommand extends Command
{
    private readonly BoardManagerInterface $boardManager;

    public function __construct(BoardManagerInterface $boardManager)
    {
        $this->boardManager = $boardManager;

        parent::__construct('start');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Start a game')
            ->addArgument('homeTeam', InputArgument::REQUIRED, 'Home team')
            ->addArgument('awayTeam', InputArgument::REQUIRED, 'Away team');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $homeTeam = $input->getArgument('homeTeam');
        \assert(\is_string($homeTeam));

        $awayTeam = $input->getArgument('awayTeam');
        \assert(\is_string($awayTeam));

        try {
            $this->boardManager->startGame($homeTeam, $awayTeam);

            $output->writeln('Game started.');

            return self::SUCCESS;
        } catch (BoardException $boardException) {
            $output->writeln(\sprintf("<error>\n\n  %s\n</error>\n", $boardException->getMessage()));

            return self::FAILURE;
        }
    }
}
