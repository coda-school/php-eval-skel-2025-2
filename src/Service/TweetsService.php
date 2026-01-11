<?php

namespace App\Service;

use App\DTO\TweetDTO;
use App\Entity\Tweets;
use App\Entity\User;
use App\Repository\FollowsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class TweetsService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
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

}
