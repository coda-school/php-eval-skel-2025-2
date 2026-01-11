<?php

namespace App\Repository;

use App\Entity\Follows;
use App\Entity\Tweets;
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

    public function findTweetsForUserFromUsersFollowed(User $user): array
    {
        return $this
            ->createQueryBuilder('f')
            ->select('t')
            ->innerJoin(User::class, 'u', 'WITH', 'f.createdBy = u.id AND u.id = :userId')
            ->innerJoin(Tweets::class, 't', 'WITH', 'f.followed = t.createdBy AND t.isDeleted = false')
            ->andWhere('f.isDeleted = false')
            ->setParameter('userId', $user->getId())
            ->orderBy('t.createdDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
