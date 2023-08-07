<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=InscriptionFormation::class, mappedBy="IdFormation")
     */
    private $inscriptionFormations;

    public function __construct()
    {
        $this->inscriptionFormations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, InscriptionFormation>
     */
    public function getInscriptionFormations(): Collection
    {
        return $this->inscriptionFormations;
    }

    public function addInscriptionFormation(InscriptionFormation $inscriptionFormation): self
    {
        if (!$this->inscriptionFormations->contains($inscriptionFormation)) {
            $this->inscriptionFormations[] = $inscriptionFormation;
            $inscriptionFormation->setIdFormation($this);
        }

        return $this;
    }

    public function removeInscriptionFormation(InscriptionFormation $inscriptionFormation): self
    {
        if ($this->inscriptionFormations->removeElement($inscriptionFormation)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionFormation->getIdFormation() === $this) {
                $inscriptionFormation->setIdFormation(null);
            }
        }

        return $this;
    }
}
