<?php

namespace App\Entity\Comptage;

use App\Entity\COmptage\Formation;
use App\Entity\Formulaire;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Comptage\VisiteurRepository")
 */
class Visiteur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message = "La valeur doit être supérieur à 0."
     * )
     */
    private $accompagnateur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formulaire", inversedBy="visiteurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formulaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comptage\Porte", inversedBy="visiteurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $porte;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Comptage\Formation", inversedBy="visiteurs", orphanRemoval=true, cascade={"remove"})
     */
    private $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccompagnateur(): ?int
    {
        return $this->accompagnateur;
    }

    public function setAccompagnateur(?int $accompagnateur): self
    {
        $this->accompagnateur = $accompagnateur;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getFormulaire(): ?Formulaire
    {
        return $this->formulaire;
    }

    public function setFormulaire(?Formulaire $formulaire): self
    {
        $this->formulaire = $formulaire;

        return $this;
    }

    public function getPorte(): ?Porte
    {
        return $this->porte;
    }

    public function setPorte(?Porte $porte): self
    {
        $this->porte = $porte;

        return $this;
    }

    /**
     * @return Collection|Formation[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->contains($formation)) {
            $this->formations->removeElement($formation);
        }

        return $this;
    }
}
