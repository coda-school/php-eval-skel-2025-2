<?php

namespace App\DTO;


use App\Entity\Tweets;

class TweetDTO
{

    public string $message;

    public static function fromEntity(Tweets $tweets): TweetDTO
    {
        // on crée une nouvelle instance de la DTO
        $dto = new self();

        // on remplit les propriétés de la DTO avec les données de l'entité
        $dto->message = $tweets->getMessage();

        return $dto;
    }

}
