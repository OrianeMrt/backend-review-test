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
    public string $display_login;

    #[Assert\NotNull]
    public string $gravatar_id;

    #[Assert\NotNull]
    #[Assert\Url]
    public string $url;

    #[Assert\NotNull]
    #[Assert\Url]
    public string $avatar_url;

    public function __construct(
        int $id,
        string $login,
        string $display_login,
        string $gravatar_id,
        string $url,
        string $avatar_url,
    ) {
        $this->id = $id;
        $this->login = $login;
        $this->display_login = $display_login;
        $this->gravatar_id = $gravatar_id;
        $this->url = $url;
        $this->avatar_url = $avatar_url;
    }
}
