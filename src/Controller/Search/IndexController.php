<?php

namespace App\Controller\Search;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'IndexControllerfehbjhbfehjb',
        ]);
    }
}
