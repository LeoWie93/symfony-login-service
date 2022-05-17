<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Exceptions\ServiceException;
use App\Exceptions\UserAlreadyExistsException;
use App\Repository\UserStorage;
use App\Services\Mail\IdpSignupMail;
use App\Services\Mail\SymfonyMailService;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class RegistrationService
{
    private UserStorage $storage;
    private SymfonyMailService $mailService;

    public function __construct(
        UserStorage        $storage,
        SymfonyMailService $mailService
    )
    {
        $this->storage = $storage;
        $this->mailService = $mailService;
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
        //TODO Implement this u lazy b*tch
        $salutation = $request->get('salutation');

        if (!$email || !$username || !$password || !$salutation) {
            throw new ServiceException('registration.credentials.notValid', 5000);
        }

        //TODO move into builder
        $user = new User();
        $user->setContactMail($email);
        $user->setUsername($username);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $user->setSalutation($salutation);
        $user->setRegistered(time());

        //TODO refactor into own function
        $uniqueString = sprintf('%s%s', $user->getUsername(), (new DateTime())->getTimestamp());
        $user->setActivationCode(hash('sha1', $uniqueString));

        if ($this->userAlreadyExists()) {
            throw new UserAlreadyExistsException();
        }

        try {
            $this->storage->save($user);
        } catch (\Exception $e) {
            //log error
            throw new ServiceException('registration.credentials.couldNotSave', 2323);
        }

        $signupMail = IdpSignupMail::fromData($user->toArray());
        $this->mailService->sendMail($signupMail);
    }

    public function activateUser(string $code)
    {
        if (!$user = $this->storage->findOneByActivationCode($code)) {
            throw new ServiceException('no.valid.activationCode', 4222);
        }

        try {
            $user->setActive();
            $user->setActivationCode(null);
            $user->setActivated(time());
            $this->storage->save($user);
            //TODO return jwt token
        } catch (\Exception $e) {
            //log error
            throw new ServiceException('registration.credentials.couldNotSave', 2323);
        }
    }

    private function userAlreadyExists()
    {
        return false;
//        if ($this->storage->findOneByEmail())
    }
}
