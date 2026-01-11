<?php

namespace App\Repository;

use App\Entity\Follows;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function getUserInformations(User $user): array {

        return $this
            ->createQueryBuilder('u')
            ->select('u.username as username', 'u.bio as bio')
            ->where('u.id = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUsersIFollow(User $user): array {
        return $this
            ->createQueryBuilder('u')
            ->select('u.username as username')
            ->innerJoin(Follows::class, 'f', 'WITH', 'f.followed = u.id')
            ->andWhere('f.isDeleted = false')
            ->andWhere('f.follower = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findUsersWhoFolloweMe(User $user): array {
        return $this
            ->createQueryBuilder('u')
            ->select('u.username as username')
            ->innerJoin(Follows::class, 'f', 'WITH', 'f.follower = u.id')
            ->andWhere('f.isDeleted = false')
            ->andWhere('f.followed = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
