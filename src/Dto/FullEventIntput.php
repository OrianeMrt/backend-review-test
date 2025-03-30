<?php

namespace App\Dto;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class FullEventIntput
{
    #[Assert\NotNull]
    #[Assert\Positive]
    public string $id;

    #[Assert\NotNull]
    public string $type;

    #[Assert\NotNull]
    public ActorInput $actor;

    #[Assert\NotNull]
    public RepoInput $repo;

    /**
     * @var array<mixed>
     */
    public array $payload;

    public bool $public;

    public DateTimeImmutable $created_at;

    public ?string $comment;

    /**
     * @param array<mixed> $payload
     */
    public function __construct(
        string $id,
        string $type,
        ActorInput $actor,
        RepoInput $repo,
        array $payload,
        bool $public,
        DateTimeImmutable $created_at,
        ?string $comment = null,
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->actor = $actor;
        $this->repo = $repo;
        $this->payload = $payload;
        $this->public = $public;
        $this->created_at = $created_at;
        $this->comment = $comment;
    }
}
