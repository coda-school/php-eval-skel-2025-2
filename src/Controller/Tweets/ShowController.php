<?php

namespace App\Controller\Tweets;

use App\Entity\Tweets;
use App\Service\LikesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;


final class ShowController extends AbstractController
{
    #[Route('/tweets/{uid}', name: 'tweets_show', methods: ['GET'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Tweets $tweet,
        LikesService $likesService
    ): Response
    {
        $connectedUser = $this->getUser();

        $like = $likesService->findIfUserLikeTweet($connectedUser, $tweet->getId());
        $isLikedByMe = ($like !== null);


        return $this->render('tweets/show/index.html.twig', [
            'tweet' => $tweet,
            'isLikedByMe' => $isLikedByMe,
        ]);
    }
}
