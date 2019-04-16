<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepository;
use App\Models\User;

class EloquentUserRepository extends EloquentRepository implements UserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
