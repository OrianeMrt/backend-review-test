<?php

namespace App\Message;

use DateTimeImmutable;

class FetchHourlyGithubEvent
{
    public function __construct(
        private DateTimeImmutable $fileDate,
    ) {
    }

    public function getFileDate(): DateTimeImmutable
    {
        return $this->fileDate;
    }
}
