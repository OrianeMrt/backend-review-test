<?php

namespace FileManager;

use App\FileManager\FileTruncator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use const PHP_EOL;

class FileTruncatorTest extends TestCase
{
    private const string FILE_PATH = __DIR__.'/test/fil_truncator.txt';

    protected function setUp(): void
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile(self::FILE_PATH, 'I am'.PHP_EOL.'the file'.PHP_EOL.'to..'.PHP_EOL.'truncate !'.PHP_EOL);
    }

    public function testTruncateBeforeOffset(): void
    {
        $fileTruncator = new FileTruncator();
        $fileTruncator->truncateBeforeOffset(self::FILE_PATH, 12);

        $this->assertFileExists(self::FILE_PATH);
        $this->assertSame('to..'.PHP_EOL.'truncate !'.PHP_EOL, file_get_contents(self::FILE_PATH));
    }

    protected function tearDown(): void
    {
        unlink(self::FILE_PATH);
    }
}
