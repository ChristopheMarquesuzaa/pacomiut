<?php

namespace App\Entity;

use App\Entity\Comptage\Formation;
use App\Entity\Comptage\Porte;
use App\Entity\Comptage\Visiteur;
use App\Entity\Satifiscation\Reponse;
use App\Entity\Satisfaction\Block;
use App\Entity\Satisfaction\Result;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormulaireRepository")
 */
class Formulaire
{
    /**
     * Un formulaire est soit :
     *  - Un formulaire de comptage
     *  - Un formulaire d'enquête.
     */
    const TYPE_COMPTAGE = 0;
    const TYPE_SATISFACTION = 1;

    const SEPARATEUR = '###';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departement", inversedBy="formulaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comptage\Visiteur", mappedBy="formulaire", orphanRemoval=true)
     */
    private $visiteurs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comptage\Porte", mappedBy="formulaire", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $portes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comptage\Formation", mappedBy="formulaire", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $formations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accompagnateur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Satisfaction\Block", mappedBy="formulaire", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $blocks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Satisfaction\Result", mappedBy="formulaire", orphanRemoval=true)
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Satifiscation\Reponse", mappedBy="formulaire")
     */
    private $reponses;

    public function __construct()
    {
        $this->visiteurs = new ArrayCollection();
        $this->portes = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->blocks = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

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

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getFormattedType(): ?string
    {
        return ($this->type == 0) ? 'Comptage' : 'Enquête';
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getFormattedActif(): ?string
    {
        return ($this->actif) ? 'Oui' : 'Non';
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
            $visiteur->setFormulaire($this);
        }

        return $this;
    }

    public function removeVisiteur(Visiteur $visiteur): self
    {
        if ($this->visiteurs->contains($visiteur)) {
            $this->visiteurs->removeElement($visiteur);
            // set the owning side to null (unless already changed)
            if ($visiteur->getFormulaire() === $this) {
                $visiteur->setFormulaire(null);
            }
        }

        return $this;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|Porte[]
     */
    public function getPortes(): Collection
    {
        return $this->portes;
    }

    public function addPorte(Porte $porte): self
    {
        if (!$this->portes->contains($porte)) {
            $this->portes[] = $porte;
            $porte->setFormulaire($this);
        }

        return $this;
    }

    public function removePorte(Porte $porte): self
    {
        if ($this->portes->contains($porte)) {
            $this->portes->removeElement($porte);
            // set the owning side to null (unless already changed)
            if ($porte->getFormulaire() === $this) {
                $porte->setFormulaire(null);
            }
        }

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
            $formation->setFormulaire($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->contains($formation)) {
            $this->formations->removeElement($formation);
            // set the owning side to null (unless already changed)
            if ($formation->getFormulaire() === $this) {
                $formation->setFormulaire(null);
            }
        }

        return $this;
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

    /**
     * @return Collection|Block[]
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function addBlock(Block $block): self
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks[] = $block;
            $block->setFormulaire($this);
        }

        return $this;
    }

    public function removeBlock(Block $block): self
    {
        if ($this->blocks->contains($block)) {
            $this->blocks->removeElement($block);
            // set the owning side to null (unless already changed)
            if ($block->getFormulaire() === $this) {
                $block->setFormulaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setFormulaire($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getFormulaire() === $this) {
                $result->setFormulaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setFormulaire($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getFormulaire() === $this) {
                $reponse->setFormulaire(null);
            }
        }

        return $this;
    }

    public function getQuestions()
    {
        $questions = new ArrayCollection();
        foreach ($this->getBlocks() as $block) {
            foreach ($block->getQuestions() as $question) {
                $questions->add($question);
            }
        }

        return $questions;
    }
}
