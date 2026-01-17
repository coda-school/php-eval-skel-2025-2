<?php

namespace App\Controller\Follows;

use App\Entity\Follows;
use App\Entity\User;
use App\Service\FollowsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    #[Route('/follows/edit/{name}', name: 'follows_edit')]
    public function index(
        #[MapEntity(mapping: ["name" => "username"])]
        User $user,
        Request $request,
        FollowsService $followsService,
    ): Response
    {
        $currentUser = $this->getUser();

        $followsService->toggleFollow($currentUser, $user);

        // On récupère l'URL précédent
        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
