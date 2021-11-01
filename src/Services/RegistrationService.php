<?php

namespace App\Services;

use App\Entity\User;
use App\Exceptions\ServiceException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class RegistrationService
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
    public function createUser(Request $request)
    {
        $request = $request->request;
        die("test");
        if (
            !$email = $request->get('email') ||
                !$username = $request->get('username') ||
                    !$password = $request->get('password') ||
                        !$salutation = $request->get('salutation')
        ) {
            die(var_dump('username'));
            throw new ServiceException('registration.credentials.notValid', 5000);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $user->setSalutation($salutation);
        try {
            $this->repository->save($user);
        } catch (\Exception $e) {
            //log error
            throw new ServiceException('registration.credentials.couldNotSave', 2323);
        }
    }
}
