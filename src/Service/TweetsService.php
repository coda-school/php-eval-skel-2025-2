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

    public function findTweetsForThread(User $user, int $page, int $limit): array {
        $limitFollowed = (80/100) * $limit;
        $limitSuggested = (20/100) * $limit;

        $followedTweets = $this->tweetsRepository->findTweetsFromFollowed($user, $page, $limitFollowed);
        $suggestedTweets = $this->tweetsRepository->findTweetsForSuggestion($user, $page, $limitSuggested);

        foreach ($followedTweets as $key => $tweet) {
            $followedTweets[$key]['isSuggestion'] = false;
        }

        foreach ($suggestedTweets as $key => $tweet) {
            $suggestedTweets[$key]['isSuggestion'] = true;
        }

        // on fusionne les deux tableaux
        $thread = array_merge($followedTweets, $suggestedTweets);

        // tri du tableau par ordre DESC (date la plus récente en premier)
        usort($thread, function ($a, $b) {
            return $b['createdDate'] <=> $a['createdDate'];
        });

        return $thread;

    }

    public function nbTotalPagesForThread(User $user, int $limit): int {
        $limitFollowed = (80/100) * $limit;
        $totalTweetsFollowed = $this->tweetsRepository->nbTotalTweetsFromFollowed($user);

        return $totalPages = ceil($totalTweetsFollowed / $limitFollowed);
    }

    public function findTop5LikeTweets(): array {
        return $this->tweetsRepository->findTop5LikeTweets();
    }

    public function findTweetsFromUser(User $user): array {
        return $this->tweetsRepository->findTweetsFromUser($user);
    }

    public function searchTweets (string $search): array {
        return $this->tweetsRepository->searchTweets($search);
    }

    public function getTweetByUid(string $tweetUid): array {
        return $this->tweetsRepository->getTweetByUid($tweetUid);
    }

}
