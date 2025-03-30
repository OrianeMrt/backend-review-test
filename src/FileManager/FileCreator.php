<?php

namespace App\FileManager;

use Symfony\Component\Filesystem\Filesystem;

class FileCreator
{
    private Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function createFile(string $fileDir, string $filename, string $content): string
    {
        if (!$this->filesystem->exists($fileDir)) {
            $this->filesystem->mkdir($fileDir, 0775);
        }

        $filePath = $fileDir.'/'.$filename;
        $this->filesystem->remove($filePath);
        $this->filesystem->appendToFile($filePath, $content);

        return $filePath;
    }
}
