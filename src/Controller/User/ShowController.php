<?php

namespace App\Controller\User;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/user', name: 'user_show', methods: ['GET'])]
    public function index(
        UserService $userService,
    ): Response
    {

        return $this->render('user/show/index.html.twig', [

        ]);
    }
}
