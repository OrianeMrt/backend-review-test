<?php

namespace App\MessageHandler;

use App\Importer\JsonGithubEventsImporter;
use App\Message\ImportGithubEvent;
use Monolog\Attribute\WithMonologChannel;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
#[WithMonologChannel('github_archive')]
class ImportGithubEventHandler
{
    public function __construct(private JsonGithubEventsImporter $githubEventsImporter)
    {
    }

    public function __invoke(ImportGithubEvent $message): void
    {
        $this->githubEventsImporter->importFromJson($message->getDataToImport());
    }
}
