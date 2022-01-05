<?php

namespace App\Services;

use App\Entity\Token;
use App\Entity\User;
use Firebase\JWT\JWT;

class JWTService
{
    private string $passphrase;

    public function __construct(string $passphrase)
    {
        $this->passphrase = $passphrase;
    }

    public function createToken(User $user): Token
    {
        $token = new Token();
        $token->setExpires(time() + 3600);

        $jwt = JWT::encode(
            [
                'iss' => 'this application',
                'exp' => $token->getExpires(),
                'sub' => $user->getId(),
                'name' => $user->getUsername(),
                'active' => $user->getActive(),
            ],
            $this->passphrase
        );

        return $token->setToken($jwt);
    }
}
