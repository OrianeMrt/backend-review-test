<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ActorInput
{
    #[Assert\NotNull]
    #[Assert\Positive]
    public int $id;

    #[Assert\NotNull]
    public string $login;

    #[Assert\NotNull]
    #[Assert\Url]
    public string $url;

    #[Assert\NotNull]
    #[Assert\Url]
    public string $avatar_url;

    public function __construct(
        int $id,
        string $login,
        string $url,
        string $avatar_url,
    ) {
        $this->id = $id;
        $this->login = $login;
        $this->url = $url;
        $this->avatar_url = $avatar_url;
    }
}
