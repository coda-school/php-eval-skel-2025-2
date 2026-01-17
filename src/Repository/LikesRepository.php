<?php

namespace App\Repository;

use App\Entity\Likes;
use App\Entity\Tweets;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Likes>
 */
class LikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Likes::class);
    }

    public function findIfUserLikeTweet (User $user, int $tweetId): ?Likes {
        return $this
            ->createQueryBuilder('l')
            ->innerJoin (User::class, 'u', 'WITH', 'l.createdBy = :userId')
            ->andwhere('l.tweet = :tweet')
            ->andWhere('l.isDeleted = false')
            ->setParameter('userId', $user->getId())
            ->setParameter('tweet', $tweetId)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
