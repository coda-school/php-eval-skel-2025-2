<?php

namespace App\Controller\Settings;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    #[Route('/settings/{uid}/edit', name: 'settings_edit', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('settings/edit/index.html.twig', [
            'controller_name' => 'User/EditController',
        ]);
    }
}
