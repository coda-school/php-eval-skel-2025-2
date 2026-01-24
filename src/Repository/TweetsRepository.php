<?php

namespace App\Repository;

use App\Entity\Follows;
use App\Entity\Likes;
use App\Entity\Tweets;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


class TweetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly EntityManagerInterface $em)
    {
        parent::__construct($registry, Tweets::class);
    }

    private const SELECT = ['u.username as authorName',
                             'u.id as authorId',
                             't.uid as uid',
                             't.id as id',
                             't.message as message',
                             't.createdDate as createdDate',
                             't.updatedDate as updatedDate',
                             'COUNT(l.id) as totalLikes'];

    public function findTweetsFromFollowed(User $user, int $page, int $limit): array
    {
        return $this
            ->createQueryBuilder('t')
            ->select(self::SELECT)
            ->innerJoin('t.createdBy', 'u')
            ->innerJoin(Follows::class, 'f', 'WITH', 'f.followed = t.createdBy AND f.follower = :userId AND f.isDeleted = false')
            ->leftJoin(Likes::class, 'l', 'WITH', 't.id = l.tweet AND l.isDeleted = false')
            ->andWhere('t.isDeleted = false')
            ->groupBy('t.id', 'u.username', 'u.id')
            ->orderBy('t.createdDate', 'DESC')
            ->setParameter('userId', $user->getId())
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findTweetsForSuggestion (User $user, int $page, int $limit): array
    {
        // subquery pour récupérer nos followers puis les exclure des tweets suggérés,
        // on doit utiliser entityManager parce qu'on part d'une autre table que Tweets
        $subQuery = $this->em->createQueryBuilder()
            ->select('followedUser.id')
            ->from(Follows::class, 'f')
            ->innerJoin('f.followed', 'followedUser')
            ->andwhere('f.follower = :user')
            ->andWhere('f.isDeleted = false');


        return $this
            ->createQueryBuilder('t')
            ->select(self::SELECT)
            ->innerJoin('t.createdBy', 'u')
            ->leftJoin(Likes::class, 'l', 'WITH', 't.id = l.tweet AND l.isDeleted = false')
            ->andWhere('u.id != :user')
            ->andWhere(($this->createQueryBuilder('t')->expr()->notIn('u.id', $subQuery->getDQL())))
            ->andWhere('t.isDeleted = false')
            ->groupBy('t.id', 'u.username', 'u.id')
            ->orderBy('t.createdDate', 'DESC')
            ->setParameter('user', $user)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function nbTotalTweetsFromFollowed (User $user): int
    {
        return $this
            ->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->innerJoin(Follows::class, 'f', 'WITH', 't.createdBy = f.followed AND f.isDeleted = false')
            ->andWhere('t.isDeleted = false')
            ->andWhere('f.follower = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTop5LikeTweets(): array {

        $dateLimit = new \DateTime('-7 days');

        return $this
            ->createQueryBuilder('t')
            ->select(self::SELECT)
            ->innerJoin('t.createdBy', 'u')
            ->leftJoin(Likes::class, 'l', 'WITH', 't.id = l.tweet AND l.isDeleted = false AND l.createdDate >= :dateLimit')
            ->andWhere('t.isDeleted = false')
            ->groupBy('t.id', 'u.username', 'u.id')
            ->orderBy('totalLikes', 'DESC')
            ->setParameter('dateLimit', $dateLimit)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findTweetsFromUser(User $user): array {
        return $this
            ->createQueryBuilder('t')
            ->select(self::SELECT)
            ->innerJoin('t.createdBy', 'u', 'WITH', 'u.id = :userId')
            ->leftJoin(Likes::class, 'l', 'WITH', 't.id = l.tweet AND l.isDeleted = false')
            ->andWhere('t.isDeleted = false')
            ->orderBy('t.createdDate', 'DESC')
            ->groupBy('t.id', 'u.username', 'u.id')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }

    public function searchTweets(string $search): array {
        return $this
            ->createQueryBuilder('t')
            ->select(self::SELECT)
            ->innerJoin('t.createdBy', 'u')
            ->leftJoin(Likes::class, 'l', 'WITH', 't.id = l.tweet AND l.isDeleted = false')
            ->andWhere('LOWER(t.message) LIKE LOWER(:search)')
            ->andWhere('t.isDeleted = false')
            ->groupBy('t.id', 'u.username', 'u.id')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }

    public function getTweetByUid (string $tweetUid): array {
        return $this
            ->createQueryBuilder('t')
            ->select(self::SELECT)
            ->innerJoin('t.createdBy', 'u')
            ->leftJoin(Likes::class, 'l', 'WITH', 't.id = l.tweet AND l.isDeleted = false')
            ->andWhere('t.uid = :tweetUid')
            ->andWhere('t.isDeleted = false')
            ->groupBy('t.id', 'u.username', 'u.id')
            ->setParameter('tweetUid', $tweetUid)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
