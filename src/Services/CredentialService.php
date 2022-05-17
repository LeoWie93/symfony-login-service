<?php

namespace App\Services;

use App\Entity\User;
use App\Exceptions\ServiceException;
use App\Repository\TokenStorage;
use App\Repository\UserStorage;

class CredentialService
{
    private JWTService $jwtService;
    private UserStorage $userStorage;
    private TokenStorage $tokenStorage;

    public function __construct(
        JWTService   $jwtService,
        UserStorage  $userStorage,
        TokenStorage $tokenStorage
    )
    {
        $this->jwtService = $jwtService;
        $this->userStorage = $userStorage;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @throws ServiceException
     */
    public function loginWithCredentials(string $username, string $password): User
    {
        $user = $this->userStorage->findOneByUsername($username);
        if ($user && password_verify($password, $user->getPassword())) {
            $token = $this->jwtService->createToken($user);
            $user->addToken($token);
            $this->tokenStorage->save($token);

            return $user;
        }

        throw new ServiceException('login failed', 4001, 401);
    }
}
