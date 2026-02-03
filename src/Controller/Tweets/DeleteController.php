<?php

namespace App\Controller\Tweets;

use App\Entity\Tweets;
use App\Service\TweetsService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteController extends AbstractController
{
    /**
     * Supprime un tweet de la base de données après avoir vérifié
     * que l'utilisateur connecté en est bien l'auteur.
     *
     * @param Tweets $tweets L'entité Tweet à supprimer (mappée via l'UID dans l'URL)
     * @param TweetsService $tweetsService Le service gérant la logique de suppression (fichiers et data)
     * * @return Response Une redirection vers la liste des tweets ou vers le tweet en cas d'erreur de droits
     */
    #[Route('/tweets/{uid}/delete', name: 'tweets_delete', methods: ['GET'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Tweets $tweets,
        TweetsService $tweetsService
    ): Response
    {
        $connectedUser = $this->getUser();

        if ($tweets->getCreatedBy() !== $connectedUser) {
            $this->addFlash("danger", "Vous n'êtes pas autorisez à supprimer ce tweet.");
            return $this->redirectToRoute('tweets_show', ['uid' => $tweets->getUid()]);
        }

        $tweetsService->deleteTweet($tweets, $connectedUser);
        $this->addFlash('success', 'Tweet supprimé avec succès !');

        return $this->redirectToRoute('tweets_list');
    }
}
