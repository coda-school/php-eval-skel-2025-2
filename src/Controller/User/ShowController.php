<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/user/{uid}', name: 'user_show', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('user/show/index.html.twig', [
            'controller_name' => 'User/ShowController',
        ]);
    }
}
