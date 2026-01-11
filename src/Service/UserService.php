<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{

    public function __construct(
        private readonly UserRepository $userRepository,
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

}
