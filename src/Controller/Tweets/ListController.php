<?php

namespace App\Controller\Tweets;

use App\DTO\TweetDTO;
use App\Entity\User;
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
        TweetsService $tweetService,
        TweetsService $tweetsService,
        LikesService $likesService,
        TweetsService $searchTweets,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 5
    ): Response
    {
        $tweetDTO = new TweetDTO();

        // création du formulaire de création d'un tweet
        $form = $this->createForm(TweetType::class, $tweetDTO);

        // traitement du formulaire par symfony, validations, etc.
        $form->handleRequest($request);

        $formSearch = $this->CreateForm(SearchType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData();
            return $this->redirectToRoute('search_tweets', ['search' => $search['rechercher']]);
        }

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // récupération des données du formulaire sous forme de la DTO TweetDTO
            $tweetDTO = $form->getData();

            $tweet = null;
            try {
                // traitements métier pour créer le tweet via le service TweetsService
                $tweet = $tweetService->createTweet($tweetDTO, $this->getUser());
            } catch (\Exception $e) {
                // en cas d'erreur, ajout d'un message flash pour indiquer l'erreur
                $this->addFlash('error', 'Erreur lors de la création du tweet');

                // redirection vers la page d'accueil
                return $this->redirectToRoute('tweets_list');
            }

            // ajout d'un message flash pour indiquer le succès de l'opération
            $this->addFlash('success', 'Tweet créé avec succès !');

            // redirection vers le détail du wallet nouvellement créé
            return $this->redirectToRoute('tweets_list');
        }

        $connectedUser = $this->getUser();

        $tweetsFollowed = $tweetsService->findTweetsForUserFromUsersFollowed($connectedUser, $page, $limit);

        foreach ($tweetsFollowed as $key => $tweet) {
            $isLiked = $likesService->findIfUserLikeTweet($connectedUser, $tweet['id']);
            $tweetsFollowed[$key]['isLikedByMe'] = ($isLiked !== null);
        }

        $ndTotalTweets = $tweetService->nbTotalTweetsForUserFromUsersFollowed($connectedUser);

        if ($ndTotalTweets <= $limit) {
            $maxPaginationPage = 1;
        } else {
            $maxPaginationPage = ceil($ndTotalTweets / $limit);
        }

        $top5Tweets = $tweetsService->findTop5LikeTweets();

        foreach ($top5Tweets as $key => $tweet) {
            $isLiked = $likesService->findIfUserLikeTweet($connectedUser, $tweet['id']);
            $top5Tweets[$key]['isLikedByMe'] = ($isLiked !== null);
        }

        return $this->render('tweets/list/index.html.twig', [
            'formSearch' => $formSearch,
            'form' => $form,
            'tweets' => $tweetsFollowed,
            'top5Tweets' => $top5Tweets,
            'limit' => $limit,
            'page' => $page,
            'maxPaginationPage' => $maxPaginationPage
        ]);
    }
}
