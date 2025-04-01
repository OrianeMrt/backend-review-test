<?php

namespace Importer;

use App\Dto\FullEventIntput;
use App\Importer\Exception\InvalidGithubEventData;
use App\Importer\JsonGithubEventsImporter;
use App\Mapper\Exception\UnwantedGithubEventException;
use App\Repository\FullEventWriterInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonGithubEventsTest extends TestCase
{
    public function testImportFromJson(): void
    {
        $fullEventInputMock = $this->createMock(FullEventIntput::class);

        $dbalFullEventWriterMock = $this->createMock(FullEventWriterInterface::class);
        $dbalFullEventWriterMock->expects($this->once())
            ->method('insertFullEvent')
            ->with($fullEventInputMock);

        $validatorMock = $this->createMock(ValidatorInterface::class);
        $validatorMock->expects($this->once())
            ->method('validate')
            ->with($fullEventInputMock);

        $serializerMock = $this->createMock(SerializerInterface::class);
        $serializerMock->expects($this->once())
            ->method('deserialize')
            ->willReturn($fullEventInputMock);

        $jsonGithubEventImporter = new JsonGithubEventsImporter($serializerMock, $validatorMock, $dbalFullEventWriterMock);

        $jsonGithubEventImporter->importFromJson('{"data": "test"}');
    }

    public function testUnwantedGithubEventException(): void
    {
        $dbalFullEventWriterMock = $this->createMock(FullEventWriterInterface::class);
        $dbalFullEventWriterMock->expects($this->never())
            ->method('insertFullEvent');

        $validatorMock = $this->createMock(ValidatorInterface::class);
        $validatorMock->expects($this->never())
            ->method('validate');

        $serializerMock = $this->createMock(SerializerInterface::class);
        $serializerMock->expects($this->once())
            ->method('deserialize')
            ->willThrowException(new UnwantedGithubEventException('Some event'));

        $jsonGithubEventImporter = new JsonGithubEventsImporter($serializerMock, $validatorMock, $dbalFullEventWriterMock);
        $jsonGithubEventImporter->importFromJson('{"date":"invalid event"}');
    }

    public function testInvalidGithubEvent(): void
    {
        $fullEventInputMock = $this->createMock(FullEventIntput::class);

        $dbalFullEventWriterMock = $this->createMock(FullEventWriterInterface::class);
        $dbalFullEventWriterMock->expects($this->never())
            ->method('insertFullEvent');

        $constraintViolationMock = $this->createMock(ConstraintViolationInterface::class);

        $this->expectException(InvalidGithubEventData::class);

        $validatorMock = $this->createMock(ValidatorInterface::class);
        $validatorMock->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList([$constraintViolationMock]));

        $serializerMock = $this->createMock(SerializerInterface::class);
        $serializerMock->expects($this->once())
            ->method('deserialize')
            ->willReturn($fullEventInputMock);

        $jsonGithubEventImporter = new JsonGithubEventsImporter($serializerMock, $validatorMock, $dbalFullEventWriterMock);
        $jsonGithubEventImporter->importFromJson('{"date":"invalid event"}');
    }
}
