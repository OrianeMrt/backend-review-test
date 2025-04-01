<?php

namespace App\Reader;

use App\Message\ImportGithubEvent;
use SplFileObject;
use Symfony\Component\Messenger\MessageBusInterface;

class GithubEventFileBatchReader
{
    public const int BATCH_SIZE = 500;
    private int $iteration = 0;

    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function readBatch(string $filePath): ?int
    {
        $file = new SplFileObject($filePath, 'r');
        $lastOffset = 0;
        $this->iteration = 0;

        while (!$file->eof() && $this->iteration < self::BATCH_SIZE) {
            $line = $file->fgets();
            $lastOffset = $file->ftell();

            if ('' === trim($line)) {
                continue;
            }

            $this->messageBus->dispatch(new ImportGithubEvent($line));
            ++$this->iteration;
        }

        return $this->iteration > 0 && false !== $lastOffset && !$file->eof() ? $lastOffset : null;
    }
}
