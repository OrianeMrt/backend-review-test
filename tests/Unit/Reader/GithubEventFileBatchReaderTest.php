<?php

namespace Reader;

use App\Reader\GithubEventFileBatchReader;
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use const PHP_EOL;

class GithubEventFileBatchReaderTest extends TestCase
{
    private const string FILE_PATH = __DIR__.'/test/batch_test.txt';

    /**
     * @dataProvider provideBatch
     */
    public function testReadBatch(int $fileRows, int $expectedNumberMessage, ?int $expectedOffset): void
    {
        $this->createTempoFile($fileRows);

        $messageBusMock = $this->createMock(MessageBusInterface::class);

        $messageBusMock
            ->expects($this->exactly($expectedNumberMessage))
            ->method('dispatch')
            ->willReturn(new Envelope(new stdClass()));

        $githubEventFileBatcher = new GithubEventFileBatchReader($messageBusMock);
        $iterationOffset = $githubEventFileBatcher->readBatch(self::FILE_PATH);

        $this->assertSame($expectedOffset, $iterationOffset);

        unlink(self::FILE_PATH);
    }

    public function provideBatch(): Generator
    {
        yield 'end-of-batch' => [10, 10, null];
        yield 'more-than-batch' => [520, 500, 9 * 2 + 90 * 3 + 401 * 4]; // 9 for 1-digit numbers, 90 for 2-digit numbers, 401 for 3 digit-numbers
    }

    private function createTempoFile(int $numberLineInFile): void
    {
        $filesystem = new Filesystem();
        $content = implode(PHP_EOL, range(1, $numberLineInFile));
        $filesystem->dumpFile(self::FILE_PATH, $content);
    }
}
