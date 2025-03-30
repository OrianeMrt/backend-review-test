<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RepoInput
{
    #[Assert\NotNull]
    #[Assert\Positive]
    public int $id;

    #[Assert\NotNull]
    public string $name;

    #[Assert\NotNull]
    #[Assert\Url]
    public string $url;

    public function __construct(
        int $id,
        string $name,
        string $url,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
    }
}
