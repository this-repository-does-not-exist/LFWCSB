<?php declare(strict_types=1);

namespace App\Console;

use App\Board\BoardException;
use App\Board\BoardManagerInterface;
use App\Game\GameException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UpdateScoreCommand extends Command
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
            ->setName('update')
            ->setDescription('Update score')
            ->addArgument('homeTeam', InputArgument::REQUIRED, 'Home team')
            ->addArgument('awayTeam', InputArgument::REQUIRED, 'Away team')
            ->addArgument('homeTeamScore', InputArgument::REQUIRED, 'Home team score')
            ->addArgument('awayTeamScore', InputArgument::REQUIRED, 'Away team score');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $homeTeam = $input->getArgument('homeTeam');
        \assert(\is_string($homeTeam));

        $awayTeam = $input->getArgument('awayTeam');
        \assert(\is_string($awayTeam));

        $homeTeamScore = $input->getArgument('homeTeamScore');
        \assert(\is_string($homeTeamScore));
        if (\preg_match('/^\d+$/', $homeTeamScore) === 0) {
            $output->writeln("<error>\n\n  Home team score must be integer!\n</error>\n");

            return self::FAILURE;
        }

        $awayTeamScore = $input->getArgument('awayTeamScore');
        \assert(\is_string($awayTeamScore));
        if (\preg_match('/^\d+$/', $awayTeamScore) === 0) {
            $output->writeln("<error>\n\n  Away team score must be integer!\n</error>\n");

            return self::FAILURE;
        }

        try {
            $this->boardManager->updateScore(
                $homeTeam,
                $awayTeam,
                (int) $homeTeamScore,
                (int) $awayTeamScore,
            );

            $output->writeln('Score updated.');

            return self::SUCCESS;
        } catch (BoardException|GameException $boardException) {
            $output->writeln(\sprintf("<error>\n\n  %s\n</error>\n", $boardException->getMessage()));

            return self::FAILURE;
        }
    }
}
