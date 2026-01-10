<?php

namespace App\Controller\Tweets;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    #[Route('/tweets/{uid}/edit', name: 'tweets_edit', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('tweets/edit/index.html.twig', [
            'controller_name' => 'Tweets/EditController',
        ]);
    }
}
