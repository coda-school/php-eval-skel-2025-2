<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function getUserInformations(User $user): array {
        return $this->userRepository->getUserInformations($user);
    }

    public function findUsersIFollow(User $user): array {
        return $this->userRepository->findUsersIFollow($user);
    }

    public function findUsersWhoFolloweMe(User $user): array {
        return $this->userRepository->findUsersWhoFolloweMe($user);
    }

    public function updateUser(User $user, UserDTO $dto): User {
        $user->setUsername($dto->username);
        $user->setBio($dto->bio);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}
