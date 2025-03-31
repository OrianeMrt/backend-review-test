<?php

namespace App\Importer\Exception;

use Exception;

class InvalidGithubEventData extends Exception
{
    public function __construct()
    {
        parent::__construct('Invaid Github Events data');
    }
}
