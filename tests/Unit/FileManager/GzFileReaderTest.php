<?php

namespace FileManager;

use App\FileManager\GzFileReader;
use PHPUnit\Framework\RiskyTestError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class GzFileReaderTest extends TestCase
{
    private const string EXPECTED_CONTENT = 'Test gz reader';
    private const string PATH_FILE = 'test.txt';

    protected function setUp(): void
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile(self::PATH_FILE, self::EXPECTED_CONTENT);
    }

    public function testRead(): void
    {
        $fakeGzipFile = $this->createGzipFile();
        if (null === $fakeGzipFile) {
            throw new RiskyTestError('Invalid test due to invalid file');
        }

        $gzFileReader = new GzFileReader();
        $iterator = iterator_to_array($gzFileReader->read($fakeGzipFile));

        $this->assertCount(1, $iterator, 'Only one line must be retrieve in gzfile');
        $this->assertSame(self::EXPECTED_CONTENT, $iterator[0], 'Gz file content must be same as '.self::EXPECTED_CONTENT);
    }

    protected function tearDown(): void
    {
        unlink(self::PATH_FILE);
        unlink(self::PATH_FILE.'.gz');
    }

    private function createGzipFile(): ?string
    {
        $gzfile = self::PATH_FILE.'.gz';
        $gzFilePath = gzopen($gzfile, 'w');

        if (false === $gzFilePath) {
            return null;
        }

        gzwrite($gzFilePath, (string) file_get_contents(self::PATH_FILE));
        gzclose($gzFilePath);

        return $gzfile;
    }
}
