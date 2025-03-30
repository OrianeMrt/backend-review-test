<?php

namespace App\FileManager;

use App\FileManager\Exceptions\UnreadableFileException;

class GzFileReader
{
    public function read(string $filePath): string
    {
        $file = gzopen($filePath, 'r');

        if (false === $file) {
            throw new UnreadableFileException('Could not read file: '.$filePath);
        }
        $content = '';

        while (!gzeof($file)) {
            $content .= gzread($file, 1024);
        }

        gzclose($file);

        return $content;
    }
}
