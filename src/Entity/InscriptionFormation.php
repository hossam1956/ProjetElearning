<?php

namespace App\Entity;

use App\Repository\InscriptionFormationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionFormationRepository::class)
 */
class InscriptionFormation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="inscriptionFormations")
     */
    private $IdFormation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inscriptionFormations")
     */
    private $IdFormateur;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inscriptionFormations")
     */
    private $IdUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFormation(): ?Formation
    {
        return $this->IdFormation;
    }

    public function setIdFormation(?Formation $IdFormation): self
    {
        $this->IdFormation = $IdFormation;

        return $this;
    }

    public function getIdFormateur(): ?User
    {
        return $this->IdFormateur;
    }

    public function setIdFormateur(?User $IdFormateur): self
    {
        $this->IdFormateur = $IdFormateur;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->IdUser;
    }

    public function setIdUser(?User $IdUser): self
    {
        $this->IdUser = $IdUser;

        return $this;
    }
}
