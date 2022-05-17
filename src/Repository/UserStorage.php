<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserStorage
{
    private UserRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(
        UserRepository         $repository,
        EntityManagerInterface $em
    )
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function save(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function remove(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function findOneByUsername(string $username): ?User
    {
        return $this->repository->findOneBy(['username' => $username]);
    }

    public function findOneByCriteria(string $contactMail)
    {
        return $this->repository->findIfAlreadyExists($contactMail);
    }

    public function findOneByActivationCode($code): ?User
    {
        return $this->repository->findOneByActivationCode($code);
    }
}
