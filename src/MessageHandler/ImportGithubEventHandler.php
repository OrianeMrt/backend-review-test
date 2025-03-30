<?php

namespace App\MessageHandler;

use App\Dto\FullEventIntput;
use App\Message\ImportGithubEvent;
use App\Repository\DbalWriteEventRepository;
use Monolog\Attribute\WithMonologChannel;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\SerializerInterface;

#[AsMessageHandler]
#[WithMonologChannel('github_archive')]
class ImportGithubEventHandler
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function __invoke(ImportGithubEvent $message): void
    {
        $deserializeEvent = $this->serializer->deserialize(
            $message->getDataToImport(),
            FullEventIntput::class,
            'json',
        );

        //            $this->dbalWriteEventRepository->insert($deserializeEvent);

        //            $deserializeEvent = $this->serializer->deserialize($message->getDataToImport(), FullEventIntput::class, 'json');
        //        $this->logger->info('Importing data with id: '.$deserializeEvent->id);
    }
}
