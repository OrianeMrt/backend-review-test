<?php

namespace App\Tests\Unit\Reader;

use App\Reader\GithubArchiveFileFetcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GithubArchiveFileFetcherTest extends TestCase
{
    private const string GH_ARCHIVE_FILE_DIR = __DIR__.'/test/gh_archive';
    private const string EXPECTED_CONTENT = 'Hi, I am the GithubArchiveFileFetcherTest !';

    public function testReadFileContent(): void
    {
        $date = DatePoint::createFromFormat('Y-m-d H', '2024-10-02 10');

        $responseMock = $this->createMock(ResponseInterface::class);

        $responseMock->method('getStatusCode')
            ->willReturn(Response::HTTP_OK);

        $responseMock->method('getContent')
            ->willReturn(self::EXPECTED_CONTENT);

        $httpClientMock = $this->createMock(HttpClientInterface::class);

        $httpClientMock->method('request')
            ->willReturn($responseMock);

        $githubArchiveFileFetcher = new GithubArchiveFileFetcher($httpClientMock, self::GH_ARCHIVE_FILE_DIR);
        $filename = $githubArchiveFileFetcher->readFileContent($date);

        $this->assertSame(self::GH_ARCHIVE_FILE_DIR.'/2024-10-02-10.json', $filename);
        $this->assertFileExists($filename);
        $this->assertSame(self::EXPECTED_CONTENT, file_get_contents($filename));
    }

    protected function tearDown(): void
    {
        unlink(self::GH_ARCHIVE_FILE_DIR.'/2024-10-02-10.json');
    }
}
