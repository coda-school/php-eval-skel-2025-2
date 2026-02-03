<?php

namespace App\DTO;


use App\Entity\Tweets;

/**
 * Objet de transfert de données pour la gestion des Tweets.
 * * Utilisé pour découpler les données du formulaire de l'entité Tweets,
 * facilitant ainsi la validation et la manipulation des fichiers (images).
 */
class TweetDTO
{

    public string $message;
    public ?string $image = null;

    public bool $removeImage = false;

    /**
     * Méthode statique permettant de créer un DTO à partir d'une entité Tweets existante.
     * * Utile pour pré-remplir les formulaires lors de l'édition.
     *
     * @param Tweets $tweets L'entité source
     * @return TweetDTO Une nouvelle instance du DTO peuplée avec les données de l'entité
     */
    public static function fromEntity(Tweets $tweets): TweetDTO
    {
        $dto = new self();

        $dto->message = $tweets->getMessage();
        $dto->image = $tweets->getImage();

        return $dto;
    }

}
