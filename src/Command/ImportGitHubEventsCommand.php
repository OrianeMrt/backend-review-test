<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\FetchHourlyGithubEvent;
use DateMalformedStringException;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * This command must import GitHub events.
 * You can add the parameters and code you want in this command to meet the need.
 */
#[AsCommand(name: self::NAME, description: 'Import GH events')]
class ImportGitHubEventsCommand extends Command
{
    public const string NAME = 'app:import-github-events';

    public function __construct(private MessageBusInterface $messageBus, ?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('date', InputArgument::OPTIONAL, 'Date to import events (format : Y-m-d)', date('Y-m-d'))
            ->addArgument('hour', InputArgument::OPTIONAL, 'Hour for import events');
    }

    /**
     * @throws DateMalformedStringException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputDate = $input->getArgument('date');
        $date = DatePoint::createFromFormat('Y-m-d', $inputDate);

        $hour = $input->getArgument('hour');

        if (null !== $hour) {
            $date = $date->setTime((int) $hour, 0);
            $this->createDateAndSubmitMessage($date);
        } else {
            for ($i = 0; $i <= 23; ++$i) {
                $date = $date->setTime($i, 0);
                $this->createDateAndSubmitMessage($date);
            }
        }

        $dateFormat = $hour ? $date->format('\f\r\o\m Y-m-d \a\t ha') : $date->format('\a\t Y-m-d');

        $output->writeln("Import $dateFormat launched !");

        return Command::SUCCESS;
    }

    private function createDateAndSubmitMessage(DatePoint $date): void
    {
        $this->messageBus->dispatch(new FetchHourlyGithubEvent($date));
    }
}
