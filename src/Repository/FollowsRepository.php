<?php

namespace App\Repository;

use App\Entity\Follows;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Follows>
 */
class FollowsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Follows::class);
    }

    public function findIfFollowerFollowFollowed (string $followerName, string $followedName): ?Follows {
        return $this
            ->createQueryBuilder('f')
            ->innerJoin('f.follower', 'u_follower')
            ->innerJoin('f.followed', 'u_followed')
            ->andwhere('u_follower.username = :followerName')
            ->andWhere('u_followed.username = :followedName')
            ->andWhere('f.isDeleted = false')
            ->setParameter('followerName', $followerName)
            ->setParameter('followedName', $followedName)
            ->getQuery()
            ->getOneOrNullResult();
        }

}
