<?php declare(strict_types=1);

namespace App\Console;

use App\Board\BoardManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SummaryCommand extends Command
{
    public const BOARD_NAME = 'Live Football World Cup Score Board';

    private readonly BoardManagerInterface $boardManager;

    public function __construct(BoardManagerInterface $boardManager)
    {
        $this->boardManager = $boardManager;

        parent::__construct('start');
    }

    protected function configure(): void
    {
        $this
            ->setName('summary')
            ->setDescription('Get a summary of games by total score');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $games = $this->boardManager->getGames();

        $table = new Table($output);
        $table->setHeaders([self::BOARD_NAME]);

        if ($games === []) {
            $table->addRow(['There are no live matches now!']);
        }

        foreach ($games as $game) {
            $table->addRow([\sprintf(
                '%s %d - %s %d',
                $game->homeTeam(),
                $game->homeTeamScore(),
                $game->awayTeam(),
                $game->awayTeamScore(),
            )]);
        }

        $table->render();

        return self::SUCCESS;
    }
}
