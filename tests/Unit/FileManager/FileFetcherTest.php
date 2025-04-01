<?php

namespace App\Tests\Unit\FileManager;

use App\FileManager\Exceptions\UnreadableFileException;
use App\FileManager\FileFetcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FileFetcherTest extends TestCase
{
    public function testFetchFileContent(): void
    {
        $filePath = 'success_test.json.gz';
        $expectedcontent = 'Content test success';

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')
            ->willReturn(Response::HTTP_OK);

        $responseMock->method('getContent')
            ->willReturn($expectedcontent);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->expects($this->once())
            ->method('request')
            ->with(Request::METHOD_GET, $filePath)
            ->willReturn($responseMock);

        $fileFetcher = new FileFetcher($httpClientMock);
        $content = $fileFetcher->fetchFileContent($filePath);

        $this->assertSame($expectedcontent, $content, 'Contet of given file not match expected content');
    }

    public function testFetchFileContentException(): void
    {
        $filePath = 'exception_test.json.gz';
        $expectedcontent = 'Content test Exception';

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')
            ->willReturn(Response::HTTP_BAD_REQUEST);

        $responseMock->method('getContent')
            ->willReturn($expectedcontent);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->expects($this->once())
            ->method('request')
            ->with(Request::METHOD_GET, $filePath)
            ->willReturn($responseMock);

        $fileFetcher = new FileFetcher($httpClientMock);

        $this->expectException(UnreadableFileException::class);
        $this->expectExceptionMessage('Could not read file exception_test.json.gz with status code: 400');

        $fileFetcher->fetchFileContent($filePath);
    }
}
