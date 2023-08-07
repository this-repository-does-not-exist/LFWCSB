<?php declare(strict_types=1);

namespace App\Console;

use App\Board\BoardManagerFactory;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\ListCommand;

final class Application extends SymfonyApplication
{
    public function __construct()
    {
        parent::__construct(SummaryCommand::BOARD_NAME, 'dev');

        $boardManager = (new BoardManagerFactory())->create();

        $this->add(new StartGameCommand($boardManager));
        $this->add(new FinishGameCommand($boardManager));
        $this->add(new UpdateScoreCommand($boardManager));
        $this->add(new SummaryCommand($boardManager));
    }

    protected function getDefaultCommands(): array
    {
        return [new ListCommand()];
    }
}
