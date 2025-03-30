<?php

namespace App\Message;

class ImportGithubEvent
{
    public function __construct(
        private string $dataToImport,
    ) {
    }

    public function getDataToImport(): string
    {
        return $this->dataToImport;
    }
}
