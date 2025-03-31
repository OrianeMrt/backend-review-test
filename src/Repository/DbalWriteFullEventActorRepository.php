<?php

namespace App\Repository;

use App\Dto\FullEventIntput;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(priority: self::PRIORITY)]
readonly class DbalWriteFullEventActorRepository implements FullEventDbalWriterInterface
{
    private const int PRIORITY = 1;

    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function insertIfNotExists(FullEventIntput $fullEventIntput): void
    {
        $actorInput = $fullEventIntput->actor;
        $insertActor = <<<SQL
        INSERT INTO actor (id, login, url, avatar_url) VALUES (:id, :login, :url, :avatar_url)
        ON CONFLICT (id) DO NOTHING
SQL;

        $this->connection->executeQuery($insertActor, [
            'id' => $actorInput->id,
            'login' => $actorInput->login,
            'url' => $actorInput->url,
            'avatar_url' => $actorInput->avatar_url,
        ]);
    }
}
