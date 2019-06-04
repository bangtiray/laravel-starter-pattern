<?php

namespace App\Repositories\Eloquent;

use App\User\UserRepository;
use App\Repositories\Contracts\UserRepositoryRepository;

use Kurt\Repoist\Repositories\Eloquent\AbstractRepository;

class EloquentUserRepositoryRepository extends AbstractRepository implements UserRepositoryRepository
{
    public function entity()
    {
        return UserRepository::class;
    }
}
