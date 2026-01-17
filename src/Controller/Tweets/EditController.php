<?php

namespace App\Controller\Tweets;

use App\DTO\TweetDTO;
use App\Entity\Tweets;
use App\Form\TweetType;
use App\Service\TweetsService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    #[Route('/tweets/{uid}/edit', name: 'tweets_edit', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Tweets $tweets,
        TweetsService $tweetsService
    ): Response
    {
        $connectedUser = $this->getUser();

        if ($tweets->getCreatedBy() !== $connectedUser) {
            throw $this->createAccessDeniedException("Ce n'est pas votre tweet !");
        }

        $dto = TweetDTO::fromEntity($tweets);
        $form =$this->createForm(TweetType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            try {
                $tweets = $tweetsService->updateTweet($tweets, $dto, $connectedUser);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur lors de la modification du tweet');

                return $this->redirectToRoute('tweets_edit', ['uid' => $tweets->getUid()]);
            }

            $this->addFlash('success', 'Success modification du tweet!');

            return $this->redirectToRoute('tweets_show', ['uid' => $tweets->getUid()]);
        }

        return $this->render('tweets/edit/index.html.twig', [
            'form' => $form,
            'tweets' => $tweets,
        ]);
    }
}
