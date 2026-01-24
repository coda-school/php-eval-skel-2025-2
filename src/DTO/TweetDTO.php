<?php

namespace App\DTO;


use App\Entity\Tweets;

class TweetDTO
{

    public string $message;
    public ?string $image = null;

    public static function fromEntity(Tweets $tweets): TweetDTO
    {
        $dto = new self();

        $dto->message = $tweets->getMessage();
        $dto->image = $tweets->getImage();

        return $dto;
    }

}
