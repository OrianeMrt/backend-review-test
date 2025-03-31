<?php

namespace App\Importer;

use App\Dto\FullEventIntput;
use App\Importer\Exception\InvalidGithubEventData;
use App\Mapper\Exception\UnwantedGithubEventException;
use App\Repository\DbalFullEventWriter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonGithubEventsImporter
{
    public function __construct(private SerializerInterface $serializer, private ValidatorInterface $validator, private DbalFullEventWriter $dbalFullEventWriter)
    {
    }

    public function importFromJson(string $jsonObject): void
    {
        try {
            $deserializedEvent = $this->serializer->deserialize(
                $jsonObject,
                FullEventIntput::class,
                JsonEncoder::FORMAT,
            );
        } catch (UnwantedGithubEventException $e) {
            return;
        }

        $error = $this->validator->validate($deserializedEvent);
        if (\count($error) > 0) {
            throw new InvalidGithubEventData();
        }

        $this->dbalFullEventWriter->insertFullEvent($deserializedEvent);
    }
}
