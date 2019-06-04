<?php

namespace App\Repositories\Eloquent;

use App\BaseRepository;
use App\Repositories\Contracts\BaseRepositoryRepository;

use Kurt\Repoist\Repositories\Eloquent\AbstractRepository;

class EloquentBaseRepositoryRepository extends AbstractRepository implements BaseRepositoryRepository
{
    public function entity()
    {
        return BaseRepository::class;
    }
}
