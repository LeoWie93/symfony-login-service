<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Exceptions\ServiceException;
use App\Repository\UserRepository;
use DateTime;
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
    public function registerUser(Request $request)
    {
        $request = $request->request;

        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');
        //TODO Implement this u lazy bitch
        $salutation = $request->get('salutation');

        if (!$email || !$username || !$password || !$salutation) {
            throw new ServiceException('registration.credentials.notValid', 5000);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $user->setSalutation($salutation);

        $uniqueString = sprintf('%s%s', $user->getUsername(), (new DateTime())->getTimestamp());
        $user->setActivationCode(hash("sha1", $uniqueString));
        try {
            $this->repository->save($user);
            //TODO trigger registration mail
        } catch (\Exception $e) {
            //log error
            throw new ServiceException('registration.credentials.couldNotSave', 2323);
        }
    }

    public function activateUser(string $code)
    {
        if (!$user = $this->repository->findByOneActivationCode($code)) {
            throw new ServiceException('no.valid.activationCode', 4222);
        }

        try {
            $user->setActive();
            $user->setActivationCode(null);
            $this->repository->save($user);
            //TODO return jwt token


        } catch (\Exception $e) {
            //log error
            throw new ServiceException('registration.credentials.couldNotSave', 2323);
        }
    }
}
