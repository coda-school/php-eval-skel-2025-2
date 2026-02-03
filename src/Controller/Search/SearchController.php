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
    /**
     * Affiche les résultats de recherche basés sur une chaîne de caractères
     * et gère la soumission du formulaire de recherche présent dans la vue.
     *
     * @param Request $request L'objet requête pour intercepter les données de formulaire
     * @param LikesService $likesService Le service pour vérifier l'état des "likes" sur les résultats
     * @param TweetsService $tweetsService Le service pour effectuer la recherche en base de données
     * @param string $search Le terme de recherche récupéré directement depuis l'URL (Query Parameter)
     * * @return Response Le rendu de la page de résultats ou une redirection vers une nouvelle recherche
     */
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
        // Récupération de la liste des tweets correspondant au terme de recherche
        $listTweets = $tweetsService->searchTweets($search);

        // Enrichissement des données pour savoir si l'utilisateur connecté a aimé chaque tweet trouvé
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
