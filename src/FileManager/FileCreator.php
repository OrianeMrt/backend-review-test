<?php

namespace App\FileManager;

use App\FileManager\Exceptions\UnreadableFileException;
use Generator;
use Symfony\Component\Filesystem\Filesystem;

class FileCreator
{
    private Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function createFile(string $fileDir, string $filename, Generator $content): string
    {
        if (!$this->filesystem->exists($fileDir)) {
            $this->filesystem->mkdir($fileDir, 0775);
        }

        $filePath = $fileDir.'/'.$filename;

        $handle = fopen($filePath, 'w');

        if (false === $handle) {
            throw new UnreadableFileException('Could not read file for writing: '.$filePath);
        }

        try {
            foreach ($content as $line) {
                fwrite($handle, $line);
            }
        } finally {
            fclose($handle);
        }

        return $filePath;
    }
}
