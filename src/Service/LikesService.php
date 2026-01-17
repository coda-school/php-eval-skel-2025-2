<?php

namespace App\Service;

use App\Entity\Likes;
use App\Entity\Tweets;
use App\Entity\User;
use App\Repository\LikesRepository;
use App\Repository\TweetsRepository;
use Doctrine\ORM\EntityManagerInterface;

class LikesService
{

    public function __construct(
        private readonly LikesRepository $likesRepository,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function findIfUserLikeTweet (User $user, int $tweetId): ?Likes {
        return $this->likesRepository->findIfUserLikeTweet($user, $tweetId);
    }

    public function toggleLike(User $user, int $tweetId): void
    {
        $isLiked = $this->likesRepository->findIfUserLikeTweet($user, $tweetId);

        if ($isLiked) {
            $isLiked->setIsDeleted(true);
            $isLiked->setDeletedBy($user);
            $isLiked->setDeletedDate(new \DateTime());
        } else {
            // On récupère l'objet Tweet correspondant à $tweetId
            $tweet = $this->em->getReference(Tweets::class, $tweetId);

            $isLiked = new Likes();
            $isLiked->setTweet($tweet);
            $isLiked->setCreatedBy($user);
            $isLiked->setCreatedDate(new \DateTime());
            $this->em->persist($isLiked);
        }

        $this->em->flush();
    }

}
