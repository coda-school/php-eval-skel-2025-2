<?php

namespace App\Controller\Follows;

use App\Entity\User;
use App\Service\FollowsService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    /**
     * Alterne l'état d'abonnement (follow/unfollow) entre l'utilisateur connecté
     * et l'utilisateur cible, puis redirige vers la page précédente.
     *
     * @param User $user L'utilisateur cible à suivre ou ne plus suivre (mappé via son username)
     * @param Request $request L'objet requête pour récupérer les informations d'en-tête (HTTP Referer)
     * @param FollowsService $followsService Le service gérant la logique métier des abonnements
     * * @return Response Une redirection vers la page d'où provient l'utilisateur
     */
    #[Route('/follows/{name}/edit', name: 'follows_edit')]
    public function index(
        #[MapEntity(mapping: ["name" => "username"])]
        User $user,
        Request $request,
        FollowsService $followsService,
    ): Response
    {
        $connectedUser = $this->getUser();

        $followsService->toggleFollow($connectedUser, $user);

        // On récupère l'URL précédent
        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
