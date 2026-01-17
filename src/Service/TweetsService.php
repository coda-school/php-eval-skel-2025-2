<?php

namespace App\Service;

use App\DTO\TweetDTO;
use App\Entity\Tweets;
use App\Entity\User;
use App\Repository\TweetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class TweetsService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TweetsRepository $tweetsRepository,
    )
    {
    }

    public function createTweet(TweetDTO $tweetDTO, User $user): Tweets {
        $tweet = new Tweets();

        $tweet->setUid(Uuid::v7()->toString());
        $tweet->setMessage($tweetDTO->message);
        $tweet->setCreatedBy($user);
        $tweet->setCreatedDate(new \DateTime());

        $this->em->persist($tweet);
        $this->em->flush();

        return $tweet;
    }

    public function updateTweet(Tweets $tweets, TweetDTO $dto, User $updater): Tweets {
        $tweets->setMessage($dto->message);
        $tweets->setUpdatedBy($updater);
        $tweets->setUpdatedDate(new \DateTime());
        $this->em->persist($tweets);
        $this->em->flush();
        return $tweets;
    }

    public function deleteTweet(Tweets $tweets, User $user): void {
        $tweets->setIsDeleted(true);
        $tweets->setDeletedBy($user);
        $tweets->setDeletedDate(new \DateTime());
        $this->em->persist($tweets);
        $this->em->flush();
    }

    public function findTweetsForUserFromUsersFollowed(User $user, int $page, int $limit): array {
        return $this->tweetsRepository->findTweetsForUserFromUsersFollowed($user, $page, $limit);
    }

    public function nbTotalTweetsForUserFromUsersFollowed(User $user): int {
        return $this->tweetsRepository->nbTotalTweetsForUserFromUsersFollowed($user);
    }

    public function findTop5LikeTweets(): array {
        return $this->tweetsRepository->findTop5LikeTweets();
    }

    public function findTweetsFromUser(User $user): array {
        return $this->tweetsRepository->findTweetsFromUser($user);
    }

}
