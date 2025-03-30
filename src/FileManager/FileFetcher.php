<?php

namespace App\FileManager;

use App\FileManager\Exceptions\UnreadableFileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FileFetcher
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function fetchFileContent(string $filePath): string
    {
        $response = $this->httpClient->request(
            Request::METHOD_GET,
            $filePath
        );

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new UnreadableFileException(\sprintf('Could not read file %s with status code: %s', $filePath, $response->getStatusCode()));
        }

        $content = $response->getContent();

        return $content;
    }
}
