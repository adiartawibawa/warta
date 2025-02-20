<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Get user by email.
     *
     * @param string $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Get user by username.
     *
     * @param string $username
     * @return mixed
     */
    public function findByUsername($username)
    {
        return $this->model->where('username', $username)->first();
    }

    /**
     * Get user by email or username.
     *
     * @param string $emailOrUsername
     * @return mixed
     */
    public function findByEmailOrUsername($emailOrUsername)
    {
        return $this->model->where('email', $emailOrUsername)
            ->orWhere('username', $emailOrUsername)
            ->first();
    }
}
