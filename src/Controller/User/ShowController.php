<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\SearchType;
use App\Service\FollowsService;
use App\Service\LikesService;
use App\Service\TweetsService;
use App\Service\UserService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    /**
     * Récupère et affiche l'ensemble des données liées à un profil utilisateur.
     *
     * @param User $user L'entité utilisateur cible (mappée via le username dans l'URL)
     * @param Request $request L'objet requête pour le formulaire de recherche
     * @param UserService $userService Service pour les infos de profil et les listes d'abonnés
     * @param TweetsService $tweetsService Service pour récupérer les tweets de l'utilisateur
     * @param FollowsService $followsService Service pour vérifier l'état du suivi (follow)
     * @param LikesService $likesService Service pour vérifier l'état des mentions "J'aime"
     * * @return Response Le rendu de la page de profil avec stats, tweets et état de suivi
     */
    #[Route('/user/{username}', name: 'user_show', methods: ['GET', 'POST'])]
    public function index(
        #[MapEntity(mapping: ['username' => 'username'])]
        User $user,
        Request $request,
        UserService $userService,
        TweetsService $tweetsService,
        FollowsService $followsService,
        LikesService $likesService
    ): Response
    {
        // --- GESTION DU FORMULAIRE DE RECHERCHE ---
        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData();
            return $this->redirectToRoute('search_tweets', ['search' => $search['rechercher']]);
        }

        // --- RÉCUPÉRATION DES STATISTIQUES ET INFOS ---
        $informationsOfUser = $userService->getUserInformations($user);

        $followedOfUser = $userService->findUsersIFollow($user);
        $nbOfFollowed = sizeof($followedOfUser);

        $followersOfUser = $userService->findUsersWhoFollowMe($user);
        $nbOfFollowers = sizeof($followersOfUser);

        $connectedUser = $this->getUser();
        $isFollowed = false;

        if ($connectedUser !== $user) {
            $isFollowed = $followsService->findIfFollowerFollowFollowed($connectedUser->getUsername(), $user->getUsername());
        }

        // --- RÉCUPÉRATION ET TRAITEMENT DES TWEETS ---
        $tweetsOfUser = $tweetsService->findTweetsFromUser($user);

        foreach ($tweetsOfUser as $key => $tweet) {
            $isLiked = $likesService->findIfUserLikeTweet($connectedUser, $tweet['id']);
            $tweetsOfUser[$key]['isLikedByMe'] = ($isLiked !== null);
        }

        return $this->render('user/show/index.html.twig', [
            'formSearch' => $formSearch,
            'informations' => $informationsOfUser,
            'tweets' => $tweetsOfUser,
            'followed' => $followedOfUser,
            'followers' => $followersOfUser,
            'nb_followed' => $nbOfFollowed,
            'nb_followers' => $nbOfFollowers,
            'is_followed' => $isFollowed,
        ]);
    }
}
