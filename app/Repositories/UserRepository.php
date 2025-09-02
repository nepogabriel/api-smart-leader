<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function register(array $user): User
    {
        $user = User::create($user);

        if (!$user)
            throw new \Exception('Já existe um usuário com este e-mail.');

        return $user;
    }
}