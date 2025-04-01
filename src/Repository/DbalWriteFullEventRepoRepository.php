<?php

namespace App\Repository;

use App\Dto\FullEventIntput;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(priority: self::PRIORITY)]
class DbalWriteFullEventRepoRepository implements FullEventDbalWriterInterface
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
        $repoInput = $fullEventIntput->repo;
        $insertRepo = <<<SQL
        INSERT INTO repo (id, name, url) VALUES (:id, :name, :url)
        ON CONFLICT (id) DO NOTHING
SQL;

        $this->connection->executeQuery($insertRepo, [
            'id' => $repoInput->id,
            'name' => $repoInput->name,
            'url' => $repoInput->url,
        ]);
    }
}
