<?php

namespace App\Entity\Satifiscation;

use App\Entity\Formulaire;
use App\Entity\Satisfaction\REsult;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Satifiscation\ReponseRepository")
 */
class Reponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formulaire", inversedBy="reponses", cascade={"remove"})
     */
    private $formulaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Satisfaction\Result", mappedBy="reponse", orphanRemoval=true, cascade={"remove"})
     */
    private $results;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|REsult[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(REsult $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setReponse($this);
        }

        return $this;
    }

    public function removeResult(REsult $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getReponse() === $this) {
                $result->setReponse(null);
            }
        }

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
}
