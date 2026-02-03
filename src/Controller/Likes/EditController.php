<?php

namespace App\Controller\Likes;

use App\Entity\Tweets;
use App\Service\LikesService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    /**
     * Alterne l'état du "Like" (ajout/suppression) pour un tweet donné
     * par l'utilisateur connecté, puis redirige vers la page d'origine.
     *
     * @param Tweets $tweet L'entité Tweet ciblée (mappée via son UID dans l'URL)
     * @param Request $request L'objet requête utilisé pour extraire le referer HTTP
     * @param LikesService $likesService Le service gérant la persistance des mentions "J'aime"
     * * @return Response Une redirection vers la page précédente (referer)
     */
    #[Route('/likes/{tweet_uid}/edit', name: 'likes_edit')]
    public function index(
        #[MapEntity(mapping: ["tweet_uid" => "uid"])]
        Tweets $tweet,
        Request $request,
        LikesService $likesService,
    ): Response
    {
        $connectedUser = $this->getUser();

        $likesService->toggleLike($connectedUser, $tweet->getId());

        // On récupère l'URL précédent
        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
