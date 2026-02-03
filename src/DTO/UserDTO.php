<?php

namespace App\DTO;

use App\Entity\User;

/**
 * Objet de transfert de données pour la gestion du profil utilisateur.
 * * Permet de transporter les informations modifiables du profil (username, bio)
 * entre les formulaires et la logique métier, sans manipuler directement
 * l'objet User persistant durant la phase de saisie.
 */
class UserDTO
{

    public string $username;
    public string $bio;

    /**
     * Crée une instance de UserDTO à partir d'une entité User.
     * * Cette méthode "Factory" est utilisée pour pré-remplir le formulaire
     * de modification avec les données actuelles de l'utilisateur.
     *
     * @param User $user L'entité utilisateur source
     * @return UserDTO L'objet de transfert de données peuplé
     */
    public static function fromEntity(User $user): UserDTO {
        $dto = new self();

        $dto->username = $user->getUsername();
        $dto->bio = $user->getBio();

        return $dto;
    }
}
