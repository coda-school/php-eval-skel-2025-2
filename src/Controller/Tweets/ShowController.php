<?php

namespace App\Controller\Tweets;

use App\Entity\Tweets;
use App\Form\SearchType;
use App\Service\LikesService;
use App\Service\TweetsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;


final class ShowController extends AbstractController
{
    #[Route('/tweets/{uid}', name: 'tweets_show', methods: ['GET', 'POST'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Tweets $tweet,
        Request $request,
        LikesService $likesService,
        TweetsService $tweetsService
    ): Response
    {
        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData();
            return $this->redirectToRoute('search_tweets', ['search' => $search['rechercher']]);
        }

        $connectedUser = $this->getUser();

        $tweetData = $tweetsService->getTweetByUid($tweet->getUid());

        $isLiked = $likesService->findIfUserLikeTweet($connectedUser, $tweetData['id']);
        $isLikedByMe = ($isLiked !== null);

        return $this->render('tweets/show/index.html.twig', [
            'formSearch' => $formSearch,
            'tweet' => $tweetData,
            'isLikedByMe' => $isLikedByMe,
        ]);
    }
}
