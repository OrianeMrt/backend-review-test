<?php

namespace App\Message;

class ReadGithubEventBatch
{
    public function __construct(
        private string $filePath,
    ) {
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
