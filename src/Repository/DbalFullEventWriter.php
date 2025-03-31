<?php

namespace App\Repository;

use App\Dto\FullEventIntput;
use Doctrine\DBAL\Connection;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final class DbalFullEventWriter
{
    /**
     * @param iterable<FullEventDbalWriterInterface> $dbalWriteFullEventObjectRepository
     */
    public function __construct(
        private Connection $connection,
        #[AutowireIterator('repository.full_event_writer')]
        private readonly iterable $dbalWriteFullEventObjectRepository,
    ) {
    }

    public function insertFullEvent(FullEventIntput $fullEventIntput): void
    {
        $this->connection->beginTransaction();

        try {
            foreach ($this->dbalWriteFullEventObjectRepository as $dbalWriter) {
                $dbalWriter->insertIfNotExists($fullEventIntput);
            }
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
