<?php

namespace App\Repository;

use App\Entity\Token;
use Doctrine\ORM\EntityManagerInterface;

class TokenStorage
{
    private TokenRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(
        TokenRepository $repository,
        EntityManagerInterface $em
    ) {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function save(Token $token)
    {
        $this->em->persist($token);
        $this->em->flush();
    }

    public function remove(Token $token)
    {
        $this->em->remove($token);
        $this->em->flush();
    }
}
