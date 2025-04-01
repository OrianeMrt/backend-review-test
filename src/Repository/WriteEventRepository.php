<?php

namespace App\Repository;

use App\Dto\EventCommentInput;

interface WriteEventRepository
{
    public function update(EventCommentInput $authorInput, int $id): void;
}
