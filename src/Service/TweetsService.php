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

    public function findTweetsForUserFromUsersFollowed(User $user): array {
        return $this->tweetsRepository->findTweetsForUserFromUsersFollowed($user);
    }

    public function findTop5LikeTweets(): array {
        return $this->tweetsRepository->findTop5LikeTweets();
    }

}
