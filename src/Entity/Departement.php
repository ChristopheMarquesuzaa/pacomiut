<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartementRepository")
 */
class Departement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=8)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 8,
     *      maxMessage = "La taille maximale du nom court est de {{ limit }}",
     * )
     */
    private $shortname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Formulaire", mappedBy="departement", orphanRemoval=true)
     */
    private $formulaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TempFormulaire", mappedBy="departement", orphanRemoval=true)
     */
    private $tempFormulaires;

    public function __construct()
    {
        $this->formulaires = new ArrayCollection();
        $this->tempFormulaires = new ArrayCollection();
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

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * @return Collection|Formulaire[]
     */
    public function getFormulaires(): Collection
    {
        return $this->formulaires;
    }

    public function addFormulaire(Formulaire $formulaire): self
    {
        if (!$this->formulaires->contains($formulaire)) {
            $this->formulaires[] = $formulaire;
            $formulaire->setDepartement($this);
        }

        return $this;
    }

    public function removeFormulaire(Formulaire $formulaire): self
    {
        if ($this->formulaires->contains($formulaire)) {
            $this->formulaires->removeElement($formulaire);
            // set the owning side to null (unless already changed)
            if ($formulaire->getDepartement() === $this) {
                $formulaire->setDepartement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TempFormulaire[]
     */
    public function getTempFormulaires(): Collection
    {
        return $this->tempFormulaires;
    }

    public function addTempFormulaire(TempFormulaire $tempFormulaire): self
    {
        if (!$this->tempFormulaires->contains($tempFormulaire)) {
            $this->tempFormulaires[] = $tempFormulaire;
            $tempFormulaire->setDepartement($this);
        }

        return $this;
    }

    public function removeTempFormulaire(TempFormulaire $tempFormulaire): self
    {
        if ($this->tempFormulaires->contains($tempFormulaire)) {
            $this->tempFormulaires->removeElement($tempFormulaire);
            // set the owning side to null (unless already changed)
            if ($tempFormulaire->getDepartement() === $this) {
                $tempFormulaire->setDepartement(null);
            }
        }

        return $this;
    }
}
