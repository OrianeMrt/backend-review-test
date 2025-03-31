<?php

namespace App\Mapper\Exception;

use Exception;

class UnwantedGithubEventException extends Exception
{
    public function __construct(string $githubEvent)
    {
        parent::__construct("The $githubEvent Github event is not desired.");
    }
}
