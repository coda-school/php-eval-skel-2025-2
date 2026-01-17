<?php

namespace App\Controller\Search;

use App\Service\TweetsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/tweets/search', name: 'search_tweets', methods: ['GET'])]
    public function index(
        TweetsService $tweetsService,
        #[MapQueryParameter] string $search,
    ): Response
    {

        $listTweets = $tweetsService->searchTweets($search);

        return $this->render('search/index.html.twig', [
            'listTweets' => $listTweets,
        ]);
    }
}
