<?php

namespace App\Services;

use App\Entity\Token;
use App\Entity\User;
use App\Exceptions\ServiceException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;

/**
 * Class CredentialService
 */
class CredentialService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private JWTService $jwtService;

    public function __construct(
        JWTService             $jwtService,
        UserRepository         $userRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->jwtService = $jwtService;
    }

    /**
     * @throws ServiceException
     */
    public function loginWithCredentials(string $username, string $password): User
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        if ($user && password_verify($password, $user->getPassword())) {
            $token = $this->jwtService->createToken($user);
            $user->addToken($token);
            $this->entityManager->persist($token);
            $this->entityManager->flush();

            return $user;
        }

        throw new ServiceException('login failed', 4001, 401);
    }
}
