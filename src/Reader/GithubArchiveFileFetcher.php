<?php

namespace App\Reader;

use App\FileManager\Exceptions\UnreadableFileException;
use App\FileManager\FileCreator;
use App\FileManager\FileFetcher;
use App\FileManager\GzFileReader;
use App\FileManager\TempFileCreator;
use DateTimeImmutable;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GithubArchiveFileFetcher
{
    public const string GH_ARCHIVE_DATE_FORMAT = 'Y-m-d-G';

    public function __construct(
        private HttpClientInterface $gharchiveClient,
        #[Autowire('%kernel.project_dir%/var/gharchive')]
        private string $ghArchiveFileDir,
    ) {
    }

    /**
     * @throws UnreadableFileException
     */
    public function readFileContent(DateTimeImmutable $date): string
    {
        $formattedDate = $date->format(self::GH_ARCHIVE_DATE_FORMAT);

        $content = (new FileFetcher($this->gharchiveClient))->fetchFileContent("/$formattedDate.json.gz");

        $tempFile = (new TempFileCreator())->createTempFile('gharchive', $content);

        $jsonContent = (new GzFileReader())->read($tempFile);

        return (new FileCreator())->createFile($this->ghArchiveFileDir, "$formattedDate.json", $jsonContent);
    }
}
