<?php

namespace App\FileManager;

use SplFileObject;
use Symfony\Component\Filesystem\Filesystem;

class FileTruncator
{
    private Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function truncateBeforeOffset(string $filePath, int $offset): void
    {
        $tempPath = $filePath.'.tmp';

        $original = new SplFileObject($filePath, 'r');
        $temp = new SplFileObject($tempPath, 'w');

        $original->fseek($offset);
        $original->fgets();

        while (!$original->eof()) {
            $line = $original->fgets();
            if ('' !== $line) {
                $temp->fwrite($line);
            }
        }

        $this->filesystem->remove($filePath);
        $this->filesystem->rename($tempPath, $filePath, true);
    }
}
