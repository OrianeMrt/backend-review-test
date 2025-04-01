<?php

namespace Repository;

use App\Dto\FullEventIntput;
use App\Repository\DbalFullEventWriter;
use App\Repository\FullEventDbalWriterInterface;
use Doctrine\DBAL\Connection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DbalFullEventWriterTest extends KernelTestCase
{
    public function testInsertFullEvent(): void
    {
        $fullEventMock = $this->createMock(FullEventIntput::class);
        $dbalFullEventWriter1Mock = $this->createMock(FullEventDbalWriterInterface::class);
        $dbalFullEventWriter2Mock = $this->createMock(FullEventDbalWriterInterface::class);

        $connectionMock = $this->createMock(Connection::class);

        $connectionMock->expects($this->once())
            ->method('commit');

        $dbalFullEventWriter2Mock->expects($this->once())
            ->method('insertIfNotExists')
            ->with($fullEventMock);

        $dbalFullEventWriter1Mock->expects($this->once())
            ->method('insertIfNotExists')
            ->with($fullEventMock);

        $connectionMock->expects($this->once())
            ->method('beginTransaction');

        $dbalFullEventWriter = new DbalFullEventWriter($connectionMock, [$dbalFullEventWriter1Mock, $dbalFullEventWriter2Mock]);
        $dbalFullEventWriter->insertFullEvent($fullEventMock);
    }

    public function testRollbackTransaction(): void
    {
        $fullEventMock = $this->createMock(FullEventIntput::class);
        $dbalFullEventWriter1Mock = $this->createMock(FullEventDbalWriterInterface::class);
        $dbalFullEventWriter2Mock = $this->createMock(FullEventDbalWriterInterface::class);

        $connectionMock = $this->createMock(Connection::class);

        $connectionMock->expects($this->never())
            ->method('commit');

        $this->expectException(Exception::class);

        $dbalFullEventWriter2Mock->expects($this->never())
            ->method('insertIfNotExists');

        $dbalFullEventWriter1Mock->expects($this->once())
            ->method('insertIfNotExists')
            ->willThrowException(new Exception());
        $connectionMock->expects($this->once())
            ->method('beginTransaction');

        $connectionMock->expects($this->once())
            ->method('rollBack');

        $dbalFullEventWriter = new DbalFullEventWriter($connectionMock, [$dbalFullEventWriter1Mock, $dbalFullEventWriter2Mock]);
        $dbalFullEventWriter->insertFullEvent($fullEventMock);
    }
}
