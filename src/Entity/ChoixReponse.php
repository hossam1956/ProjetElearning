<?php

namespace App\Entity;

use App\Repository\ChoixReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChoixReponseRepository::class)]
class ChoixReponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $choix = null;

    #[ORM\ManyToOne(inversedBy: 'choix_reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoix(): ?string
    {
        return $this->choix;
    }

    public function setChoix(string $choix): static
    {
        $this->choix = $choix;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }
}
