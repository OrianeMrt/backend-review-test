<?php

namespace App\FileManager;

use App\FileManager\Exceptions\UnreadableFileException;
use Generator;

class GzFileReader
{
    public function read(string $filePath): Generator
    {
        $handle = gzopen($filePath, 'r');

        if (false === $handle) {
            throw new UnreadableFileException('Could not read file: '.$filePath);
        }
        $content = [];

        while (!gzeof($handle)) {
            yield gzread($handle, 8192);
        }

        gzclose($handle);
    }
}
