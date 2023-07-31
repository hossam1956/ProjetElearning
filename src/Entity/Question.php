<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $question = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $reponse = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: ChoixReponse::class)]
    private Collection $choix_reponses;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercice $exercice = null;

    public function __construct()
    {
        $this->choix_reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?int
    {
        return $this->reponse;
    }

    public function setReponse(int $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * @return Collection<int, ChoixReponse>
     */
    public function getChoixReponses(): Collection
    {
        return $this->choix_reponses;
    }

    public function addChoixReponse(ChoixReponse $choixReponse): static
    {
        if (!$this->choix_reponses->contains($choixReponse)) {
            $this->choix_reponses->add($choixReponse);
            $choixReponse->setQuestion($this);
        }

        return $this;
    }

    public function removeChoixReponse(ChoixReponse $choixReponse): static
    {
        if ($this->choix_reponses->removeElement($choixReponse)) {
            // set the owning side to null (unless already changed)
            if ($choixReponse->getQuestion() === $this) {
                $choixReponse->setQuestion(null);
            }
        }

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): static
    {
        $this->exercice = $exercice;

        return $this;
    }
}
