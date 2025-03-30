<?php

namespace App\MessageHandler;

use App\FileManager\FileTruncator;
use App\Message\ReadGithubEventBatch;
use App\Reader\GithubEventFileBatchReader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ReadGithubEventBatchHandler
{
    public function __construct(
        private GithubEventFileBatchReader $githubEventFileBatchReader,
        private FileTruncator $fileTruncator,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(ReadGithubEventBatch $message): void
    {
        $filePath = $message->getFilePath();

        $lastOffset = $this->githubEventFileBatchReader->readBatch($filePath);

        if (null !== $lastOffset) {
            $this->fileTruncator->truncateBeforeOffset($filePath, $lastOffset);
            $this->messageBus->dispatch(new ReadGithubEventBatch($filePath));
        }
    }
}
