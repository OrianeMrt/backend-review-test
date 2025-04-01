<?php

namespace App\Repository;

use App\Dto\FullEventIntput;

interface FullEventWriterInterface
{
    public function insertFullEvent(FullEventIntput $fullEventIntput): void;
}
