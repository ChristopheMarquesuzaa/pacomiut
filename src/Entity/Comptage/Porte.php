<?php

namespace App\Entity\Comptage;

use App\Entity\Formulaire;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Comptage\PorteRepository")
 */
class Porte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formulaire", inversedBy="portes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formulaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comptage\Visiteur", mappedBy="porte", cascade={"remove"})
     */
    private $visiteurs;

    public function __construct()
    {
        $this->visiteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Visiteur[]
     */
    public function getVisiteurs(): Collection
    {
        return $this->visiteurs;
    }

    public function addVisiteur(Visiteur $visiteur): self
    {
        if (!$this->visiteurs->contains($visiteur)) {
            $this->visiteurs[] = $visiteur;
            $visiteur->setPorte($this);
        }

        return $this;
    }

    public function removeVisiteur(Visiteur $visiteur): self
    {
        if ($this->visiteurs->contains($visiteur)) {
            $this->visiteurs->removeElement($visiteur);
            // set the owning side to null (unless already changed)
            if ($visiteur->getPorte() === $this) {
                $visiteur->setPorte(null);
            }
        }

        return $this;
    }
}
