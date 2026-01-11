<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Service\TweetsService;
use App\Service\UserService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/user/{username}', name: 'user_show', methods: ['GET'])]
    public function index(
        #[MapEntity(mapping: ['username' => 'username'])]
        User $user,
        UserService $userService,
        TweetsService $tweetsService,
    ): Response
    {
        $informationsOfUser = $userService->getUserInformations($user);

        $tweetsOfUser = $tweetsService->findTweetsFromConnectedUser($user);

        $followedOfUser = $userService->findUsersIFollow($user);

        $followersOfUser = $userService->findUsersWhoFolloweMe($user);

        return $this->render('user/show/index.html.twig', [
            'informations' => $informationsOfUser,
            'tweets' => $tweetsOfUser,
            'followed' => $followedOfUser,
            'followers' => $followersOfUser,
        ]);
    }
}
