<?php

namespace App\Repository;

use App\Dto\EventCommentInput;
use App\Dto\FullEventIntput;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(priority: self::PRIORITY)]
readonly class DbalWriteEventRepository implements WriteEventRepository, FullEventDbalWriterInterface
{
    private const int PRIORITY = 0;

    public function __construct(private Connection $connection)
    {
    }

    public function update(EventCommentInput $authorInput, int $id): void
    {
        $sql = <<<SQL
        UPDATE event
        SET comment = :comment
        WHERE id = :id
SQL;

        $this->connection->executeQuery($sql, ['id' => $id, 'comment' => $authorInput->comment]);
    }

    /**
     * @throws Exception
     */
    public function insertIfNotExists(FullEventIntput $fullEventInput): void
    {
        $insertEvent = <<<SQL
        INSERT INTO event (id, type, count, actor_id, repo_id, payload, create_at, comment) VALUES (:id, :type, 1, :actor_id, :repo_id, :payload, :create_at, :comment)
        ON CONFLICT (id) DO NOTHING
SQL;
        $dateFormat = $this->connection->getDatabasePlatform()->getDateTimeFormatString();

        $this->connection->executeQuery($insertEvent, [
            'id' => $fullEventInput->id,
            'type' => $fullEventInput->type,
            'actor_id' => $fullEventInput->actor->id,
            'repo_id' => $fullEventInput->repo->id,
            'payload' => json_encode($fullEventInput->payload),
            'create_at' => $fullEventInput->created_at->format($dateFormat),
            'comment' => $fullEventInput->comment,
        ]);
    }
}
