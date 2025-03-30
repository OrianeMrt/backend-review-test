<?php

namespace App\MessageHandler;

use App\FileManager\Exceptions\UnreadableFileException;
use App\Message\FetchHourlyGithubEvent;
use App\Message\ReadGithubEventBatch;
use App\Reader\GithubArchiveFileFetcher;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class FetchHourlyGithubEventHandler
{
    public function __construct(
        private GithubArchiveFileFetcher $githubArchiveEventReader,
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws UnreadableFileException
     */
    public function __invoke(FetchHourlyGithubEvent $message): void
    {
        $filePath = $this->githubArchiveEventReader->readFileContent($message->getFileDate());
        $this->messageBus->dispatch(new ReadGithubEventBatch($filePath));
    }
}
