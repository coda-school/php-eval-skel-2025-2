<?php

namespace App\Controller\Tweets;

use App\DTO\TweetDTO;
use App\Entity\Tweets;
use App\Service\TweetsService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    #[Route('/tweets/{uid}/edit', name: 'tweets_edit', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Tweets $tweets,
        TweetsService $tweetsService
    ): Response
    {
        return $this->render('tweets/edit/index.html.twig', [
            'controller_name' => 'Tweets/EditController',
        ]);
    }
}
