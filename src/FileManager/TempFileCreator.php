<?php

namespace App\FileManager;

class TempFileCreator
{
    public function createTempFile(string $prefix, string $content): string
    {
        $tmpFile = tempnam(sys_get_temp_dir(), $prefix);
        file_put_contents($tmpFile, $content);

        return $tmpFile;
    }
}
