<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail($email);

    public function findByUsername($username);

    public function findByEmailOrUsername($emailOrUsername);
}
