<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\FollowsRepository;

class FollowsService
{
    public function __construct(
        private readonly FollowsRepository $followsRepository,
    )
    {
    }
    public function findTweetsForUserFromUsersFollowed(User $user): array {
        return $this->followsRepository->findTweetsForUserFromUsersFollowed($user);
    }

}
