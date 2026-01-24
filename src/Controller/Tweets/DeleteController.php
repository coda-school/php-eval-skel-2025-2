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
