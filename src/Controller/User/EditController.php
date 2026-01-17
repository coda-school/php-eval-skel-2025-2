<?php

namespace App\Controller\User;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    #[Route('/user/{username}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function index(
        #[MapEntity(mapping: ['username' => 'username'])]
        User $user,
        UserService $userService,
        Request $request
    ): Response
    {
        $connectedUser = $this->getUser();

        if ($user->getUsername() !== $connectedUser->getUsername()) {
            $this->addFlash("danger", "Vous n'êtes pas autorisez à modifier ce profile.");
            return $this->redirectToRoute('user_show', ['username' => $connectedUser->getUsername()]);
        }

        $dtoUser = UserDTO::fromEntity($user);

        $form = $this->createForm(UserType::class, $dtoUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dtoUser = $form->getData();
            try {
                $user = $userService->updateUser($user, $dtoUser);
            } catch (\Exception $e) {
                $this->addFlash("danger", "erreur lors de la modification du profil");
                return $this->redirectToRoute('user_show', ['username' => $connectedUser->getUsername()]);
            }
        }

        return $this->render('user/edit/index.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }
}
