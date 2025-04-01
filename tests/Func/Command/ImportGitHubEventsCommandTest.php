<?php

declare(strict_types=1);

namespace App\Tests\Func\Command;

use App\Command\ImportGitHubEventsCommand;
use Generator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\Console\Tester\CommandTester;

class ImportGitHubEventsCommandTest extends KernelTestCase
{
    /**
     * @dataProvider provideArguments
     *
     * @param array{0: array{date: string, hour: int}, 1: int, 2: string} $commandArguments
     */
    public function testExecute(array $commandArguments, int $expectedNumberMessage, string $expectedBodyDate): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find(ImportGitHubEventsCommand::NAME);
        $commandTester = new CommandTester($command);

        $commandTester->execute($commandArguments);
        $commandTester->assertCommandIsSuccessful();

        $this->checkCreatedQueueMessage($expectedNumberMessage, $expectedBodyDate);
    }

    private function checkCreatedQueueMessage(int $expectedNumberMessage, string $expectedBodyDate): void
    {
        $dbal = static::getContainer()->get('doctrine.dbal.default_connection');

        $sql = <<<SQL
        SELECT count(id) as number_messages
        FROM messenger_messages
        WHERE queue_name = :queue_name
        AND date(created_at) = :date
        AND body LIKE :body_date
SQL;

        $queryResult = $dbal->fetchOne($sql, [
            'queue_name' => 'fetch_file',
            'date' => (new DatePoint())->format('Y-m-d'),
            'body_date' => "%$expectedBodyDate%",
        ]);

        $this->assertSame($expectedNumberMessage, $queryResult, 'Number of messages in queue not match expected number of messages in queue.');
    }

    public function provideArguments(): Generator
    {
        yield 'no-argument' => [[], 24, (new DatePoint())->format('Y-m-d')];
        yield 'one-day-without-hour' => [['date' => '2024-10-02'], 24, '2024-10-02'];
        yield 'one-day-at-one' => [['date' => '2023-10-02', 'hour' => 1], 1, '2023-10-02'];
    }
}
