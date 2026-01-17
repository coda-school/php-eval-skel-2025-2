<?php

namespace App\Repository;

use App\Entity\Follows;
use App\Entity\Likes;
use App\Entity\Tweets;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class TweetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tweets::class);
    }

    public function findTweetsForUserFromUsersFollowed(User $user, int $page, int $limit): array
    {
        return $this
            ->createQueryBuilder('t')
            ->select('t', 'u.username as authorName', 't.uid as uid','t.message as message', 't.createdDate as createdDate', 'COUNT(l.id) as totalLikes')
            ->innerJoin('t.createdBy', 'u')
            ->innerJoin(Follows::class, 'f', 'WITH', 'f.followed = t.createdBy AND f.follower = :userId')
            ->leftJoin(Likes::class, 'l', 'WITH', 't.id = l.tweet AND l.isDeleted = false')
            ->andWhere('f.isDeleted = false')
            ->andWhere('t.isDeleted = false')
            ->groupBy('t.id', 'u.username')
            ->orderBy('t.createdDate', 'DESC')
            ->setParameter('userId', $user->getId())
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function nbTotalTweetsForUserFromUsersFollowed(User $user): int
    {
        return $this
            ->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->innerJoin(Follows::class, 'f', 'WITH', 't.createdBy = f.followed AND f.isDeleted = false')
            ->andWhere('t.isDeleted = false')
            ->andWhere('f.createdBy = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTop5LikeTweets(): array {
        return $this
            ->createQueryBuilder('t')
            ->select('t', 'u.username as authorName', 't.uid as uid','t.message as message', 't.createdDate as createdDate', 'COUNT(l.id) as totalLikes')
            ->innerJoin('t.createdBy', 'u')
            ->leftJoin(Likes::class, 'l', 'WITH', 't.id = l.tweet')
            ->andWhere('t.isDeleted = false')
            ->andWhere('l.isDeleted = false')
            ->groupBy('t.id', 'u.username')
            ->orderBy('totalLikes', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findTweetsFromConnectedUser(User $user): array {
        return $this
            ->createQueryBuilder('t')
            ->select('t', 'u.username as authorName','t.message as message', 't.createdDate as createdDate', 'COUNT(l.id) as totalLikes')
            ->innerJoin(User::class, 'u', 'WITH', 'u.id = t.createdBy AND u.id = :userId')
            ->leftJoin(Likes::class, 'l', 'WITH', 't.id = l.tweet AND l.isDeleted = false')
            ->andWhere('t.isDeleted = false')
            ->orderBy('t.createdDate', 'DESC')
            ->groupBy('t.id', 'u.username')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }
}
