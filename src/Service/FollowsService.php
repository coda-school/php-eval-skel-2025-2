<?php

namespace App\Service;


use App\Entity\Follows;
use App\Repository\FollowsRepository;

class FollowsService
{
    public function __construct(
        private readonly FollowsRepository $followsRepository,
    )
    {
    }

    public function findIfFollowerFollowFollowed (string $followerName, string $followedName): ?Follows {
        return $this->followsRepository->findIfFollowerFollowFollowed($followerName, $followedName);
    }

}
