<?php

namespace App\Service;


use App\Entity\Follows;
use App\Entity\User;
use App\Repository\FollowsRepository;
use Doctrine\ORM\EntityManagerInterface;

class FollowsService
{
    public function __construct(
        private readonly FollowsRepository $followsRepository,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function findIfFollowerFollowFollowed (string $followerName, string $followedName): ?Follows {
        return $this->followsRepository->findIfFollowerFollowFollowed($followerName, $followedName);
    }

    public function toggleFollow(User $currentUser, User $userToFollow): void
    {
        $isFollowed = $this->followsRepository->findIfFollowerFollowFollowed(
            $currentUser->getUsername(),
            $userToFollow->getUsername()
        );

        if ($isFollowed) {
            $isFollowed->setIsDeleted(true);
            $isFollowed->setDeletedBy($currentUser);
            $isFollowed->setDeletedDate(new \DateTime());
        } else {
            $isFollowed = new Follows();
            $isFollowed->setCreatedBy($currentUser);
            $isFollowed->setCreatedDate(new \DateTime());
            $isFollowed->setFollowed($userToFollow);
            $isFollowed->setFollower($currentUser);
            $this->em->persist($isFollowed);
        }

        $this->em->flush();
    }

}
