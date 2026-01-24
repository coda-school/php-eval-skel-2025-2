<?php

namespace App\Controller\Tweets;

use App\DTO\TweetDTO;
use App\Form\SearchType;
use App\Form\TweetType;
use App\Service\LikesService;
use App\Service\TweetsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class ListController extends AbstractController
{
    #[Route('/tweets', name: 'tweets_list', methods: ['GET', 'POST'])]
    public function index(
        Request       $request,
        TweetsService $tweetsService,
        LikesService $likesService,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 10
    ): Response
    {
        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData();
            return $this->redirectToRoute('search_tweets', ['search' => $search['rechercher']]);
        }

        $tweetDTO = new TweetDTO();
        $form = $this->createForm(TweetType::class, $tweetDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('image')->getData();

            try {


                if ($imgFile) {
                    $imgFileName = $tweetsService->upload($imgFile);
                    $tweetDTO->image = $imgFileName;
                }

                $tweetsService->createTweet($tweetDTO, $this->getUser());
                $this->addFlash('success', 'Tweet créé avec succès !');
                return $this->redirectToRoute('tweets_list');

            } catch (\Exception) {
                $this->addFlash('error', 'Erreur lors de la création du tweet');
                return $this->redirectToRoute('tweets_list');
            }
        }

        $connectedUser = $this->getUser();

        $tweetsForThread = $tweetsService->findTweetsForThread($connectedUser, $page, $limit);

        foreach ($tweetsForThread as $key => $tweet) {
            $isLiked = $likesService->findIfUserLikeTweet($connectedUser, $tweet['id']);
            $tweetsForThread[$key]['isLikedByMe'] = ($isLiked !== null);
        }

        $maxPaginationPage = $tweetsService->nbTotalPagesForThread($connectedUser, $limit);

        if ($maxPaginationPage <= 1) {
            $maxPaginationPage = 1;
        }

        $top5Tweets = $tweetsService->findTop5LikeTweets();

        foreach ($top5Tweets as $key => $tweet) {
            $isLiked = $likesService->findIfUserLikeTweet($connectedUser, $tweet['id']);
            $top5Tweets[$key]['isLikedByMe'] = ($isLiked !== null);
        }

        return $this->render('tweets/list/index.html.twig', [
            'formSearch' => $formSearch,
            'form' => $form,
            'tweets' => $tweetsForThread,
            'top5Tweets' => $top5Tweets,
            'limit' => $limit,
            'page' => $page,
            'maxPaginationPage' => $maxPaginationPage
        ]);
    }
}
