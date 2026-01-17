<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Service\FollowsService;
use App\Service\LikesService;
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
        FollowsService $followsService,
        LikesService $likesService
    ): Response
    {
        $informationsOfUser = $userService->getUserInformations($user);

        $tweetsOfUser = $tweetsService->findTweetsFromUser($user);

        $followedOfUser = $userService->findUsersIFollow($user);

        $nbOfFollowed = sizeof($followedOfUser);

        $followersOfUser = $userService->findUsersWhoFolloweMe($user);

        $nbOfFollowers = sizeof($followersOfUser);

        $connectedUser = $this->getUser();

        $isFollowed = false;

        if ($connectedUser !== $user) {
            $isFollowed = $followsService->findIfFollowerFollowFollowed($connectedUser->getUsername(), $user->getUsername());
        }

        foreach ($tweetsOfUser as $key => $tweet) {
            $isLiked = $likesService->findIfUserLikeTweet($connectedUser, $tweet['id']);
            $tweetsOfUser[$key]['isLikedByMe'] = ($isLiked !== null);
        }

        return $this->render('user/show/index.html.twig', [
            'informations' => $informationsOfUser,
            'tweets' => $tweetsOfUser,
            'followed' => $followedOfUser,
            'followers' => $followersOfUser,
            'nb_followed' => $nbOfFollowed,
            'nb_followers' => $nbOfFollowers,
            'is_followed' => $isFollowed,
        ]);
    }
}
