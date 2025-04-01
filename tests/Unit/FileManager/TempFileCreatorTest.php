<?php

namespace FileManager;

use App\FileManager\TempFileCreator;
use PHPUnit\Framework\TestCase;

class TempFileCreatorTest extends TestCase
{
    public function testCreateTempFile(): void
    {
        $prefix = 'tempfile_test';
        $content = 'tempFilecontent';

        $tempFileCreator = new TempFileCreator();
        $tempFile = $tempFileCreator->createTempFile($prefix, $content);

        $this->assertFileExists($tempFile, 'Temporary file must be created');
        $this->assertSame($content, file_get_contents($tempFile), 'Content of temporary file not match');

        unlink($tempFile);
    }
}
