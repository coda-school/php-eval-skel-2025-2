<?php

namespace App\Controller\Search;

use App\Service\LikesService;
use App\Service\TweetsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/tweets/search', name: 'search_tweets', methods: ['GET'])]
    public function index(
        LikesService $likesService,
        TweetsService $tweetsService,
        #[MapQueryParameter] string $search,
    ): Response
    {

        $connectedUser = $this->getUser();

        $listTweets = $tweetsService->searchTweets($search);

        foreach ($listTweets as $key => $tweet) {
            $isLiked = $likesService->findIfUserLikeTweet($connectedUser, $tweet['id']);
            $listTweets[$key]['isLikedByMe'] = ($isLiked !== null);
        }


        return $this->render('search/index.html.twig', [
            'listTweets' => $listTweets,
        ]);
    }
}
