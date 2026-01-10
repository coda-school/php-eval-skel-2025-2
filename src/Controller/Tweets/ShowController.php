<?php

namespace App\Controller\Tweets;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/tweets/{uid}', name: 'tweets_show', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('tweets/show/index.html.twig', [
            'controller_name' => 'Tweets/ShowController',
        ]);
    }
}
