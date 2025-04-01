<?php

namespace App\Importer\Exception;

use Exception;

class InvalidGithubEventData extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid Github Events data');
    }
}
