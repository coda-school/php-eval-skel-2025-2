<?php

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    /**
     * Affiche la page d'accueil principale de l'application.
     * * Cette méthode sert de point d'entrée pour les utilisateurs non connectés
     * ou redirigés vers la racine du domaine.
     *
     * @return Response Le rendu du template Twig de la page d'accueil
     */
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
