<?php

namespace App\Entity;

use App\Entity\Impl\BaseEntity;
use App\Repository\LikesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikesRepository::class)]
class Likes extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Tweets $tweet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTweet(): ?Tweets
    {
        return $this->tweet;
    }

    public function setTweet(?Tweets $tweet): static
    {
        $this->tweet = $tweet;

        return $this;
    }
}
