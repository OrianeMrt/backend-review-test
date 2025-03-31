<?php

namespace App\Mapper;

use App\Entity\EventType;
use App\Mapper\Exception\UnwantedGithubEventException;

final class GithubEventTypeMapper
{
    public const string COMMIT = 'PushEvent';
    public const string COMMENT = 'CommitCommentEvent';
    public const string ISSUE_COMMENT = 'IssueCommentEvent';
    public const string PR_COMMENT = 'PullRequestReviewCommentEvent';
    public const string PULL_REQUEST = 'PullRequestEvent';

    /**
     * @var array<string>
     */
    public const array GITHUB_EVENT_TYPES = [
        self::COMMIT,
        self::COMMENT,
        self::ISSUE_COMMENT,
        self::PR_COMMENT,
        self::PULL_REQUEST,
    ];

    public static function map(string $eventType): string
    {
        return match ($eventType) {
            self::COMMIT => EventType::COMMIT,
            self::COMMENT,
            self::ISSUE_COMMENT,
            self::PR_COMMENT => EventType::COMMENT,
            self::PULL_REQUEST => EventType::PULL_REQUEST,
            default => throw new UnwantedGithubEventException($eventType),
        };
    }
}
