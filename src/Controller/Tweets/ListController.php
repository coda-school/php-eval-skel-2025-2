<?php

namespace App\Controller\Tweets;

use App\DTO\TweetDTO;
use App\Form\TweetType;
use App\Service\TweetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListController extends AbstractController
{
    #[Route('/tweets', name: 'tweets_list', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        TweetService $tweetService,
    ): Response
    {
        $tweetDTO = new TweetDTO();

        // création du formulaire de création d'un tweet
        $form = $this->createForm(TweetType::class, $tweetDTO);

        // traitement du formulaire par symfony, validations, etc.
        $form->handleRequest($request);

        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // récupération des données du formulaire sous forme de la DTO TweetDTO
            $tweetDTO = $form->getData();

            $tweet = null;
            try {
                // traitements métier pour créer le tweet via le service TweetService
                $tweet = $tweetService->createTweet($tweetDTO, $this->getUser());
            } catch (\Exception $e) {
                // en cas d'erreur, ajout d'un message flash pour indiquer l'erreur
                $this->addFlash('error', 'Erreur lors de la création du tweet');

                // redirection vers la page d'accueil
                return $this->redirectToRoute('tweets_list');
            }

            // ajout d'un message flash pour indiquer le succès de l'opération
            $this->addFlash('success', 'Tweet créé avec succès !');

            // redirection vers le détail du wallet nouvellement créé
            return $this->redirectToRoute('tweets_list');
        }


        return $this->render('tweets/list/index.html.twig', [
            'form' => $form
        ]);
    }
}
