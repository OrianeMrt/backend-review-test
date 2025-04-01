<?php

namespace FileManager;

use App\FileManager\FileCreator;
use Generator;
use PHPUnit\Framework\TestCase;

class FileCreatorTest extends TestCase
{
    public const string FILENAME = 'file_creator.txt';
    public const string FILE_DIR = __DIR__.'/test';
    public const string EXPECTED_FILE_PATH = __DIR__.'/test/file_creator.txt';

    public function testCreateFile(): void
    {
        $content = $this->getFakeContent();

        $fileCreator = new FileCreator();
        $filePath = $fileCreator->createFile(self::FILE_DIR, self::FILENAME, $content);

        $this->assertSame(self::EXPECTED_FILE_PATH, $filePath);
        $this->assertFileExists(self::EXPECTED_FILE_PATH);
        $this->assertSame('I am The Fake Content !', file_get_contents(self::EXPECTED_FILE_PATH));
    }

    protected function tearDown(): void
    {
        unlink(self::EXPECTED_FILE_PATH);
    }

    private function getFakeContent(): Generator
    {
        yield 'I am ';
        yield 'The ';
        yield 'Fake ';
        yield 'Content ';
        yield '!';
    }
}
