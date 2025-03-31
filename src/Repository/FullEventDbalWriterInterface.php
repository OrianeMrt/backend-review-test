<?php

namespace App\Repository;

use App\Dto\FullEventIntput;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('repository.full_event_writer')]
interface FullEventDbalWriterInterface
{
    public function insertIfNotExists(FullEventIntput $fullEventIntput): void;
}
