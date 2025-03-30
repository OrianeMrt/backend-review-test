<?php

namespace App\Repository;

use App\Dto\EventCommentInput;
use App\Dto\FullEventIntput;
use Doctrine\DBAL\Connection;

readonly class DbalWriteEventRepository implements WriteEventRepository
{
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

    public function insert(FullEventIntput $fullEventInput): void
    {
        $sql = <<<SQL
        INSERT INTO event (id, comment) VALUES (:id, :comment)
SQL;

        $this->connection->executeQuery($sql, ['id' => $fullEventInput->id, 'comment' => $fullEventInput->comment]);
    }
}
