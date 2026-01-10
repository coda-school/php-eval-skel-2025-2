<?php

namespace App\Controller\Tweets;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListController extends AbstractController
{
    #[Route('/tweets', name: 'tweets_list', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('tweets/list/index.html.twig', [
            'controller_name' => 'Tweets/ListController',
        ]);
    }
}
