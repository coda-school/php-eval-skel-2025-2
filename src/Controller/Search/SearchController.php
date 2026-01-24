<?php

namespace App\Controller\Search;

use App\Form\SearchType;
use App\Service\LikesService;
use App\Service\TweetsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/tweets/search', name: 'search_tweets', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        LikesService $likesService,
        TweetsService $tweetsService,
        #[MapQueryParameter] string $search,
    ): Response
    {
        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData();
            return $this->redirectToRoute('search_tweets', ['search' => $search['rechercher']]);
        }

        $connectedUser = $this->getUser();
        $listTweets = $tweetsService->searchTweets($search);

        foreach ($listTweets as $key => $tweet) {
            $isLiked = $likesService->findIfUserLikeTweet($connectedUser, $tweet['id']);
            $listTweets[$key]['isLikedByMe'] = ($isLiked !== null);
        }

        return $this->render('search/index.html.twig', [
            'formSearch' => $formSearch,
            'listTweets' => $listTweets,
        ]);
    }
}
