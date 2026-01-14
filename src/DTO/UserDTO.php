<?php

namespace App\DTO;

use App\Entity\User;

class UserDTO
{

    public string $username;
    public string $bio;
    public static function fromEntity(User $user): UserDTO {
        $dto = new self();

        $dto->username = $user->getUsername();
        $dto->bio = $user->getBio();

        return $dto;
    }
}
