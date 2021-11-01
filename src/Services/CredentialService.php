<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Repository\UserRepository;

/**
 * Class CredentialService
 */
class CredentialService
{
    private UserRepository $repository;

    public function __construct(
        UserRepository $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     * @throws ServiceException
     */
    public function loginWithCredentials(string $username, string $password)
    {
        $user = $this->repository->findOneBy(['username' => $username]);

        if ($user && password_verify($password, $user->getPassword())) {
            //create jwt etc and return
            return true;
        }

        throw new ServiceException('login failed', 4001, 401);
    }
}
