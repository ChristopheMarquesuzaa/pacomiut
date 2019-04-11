<?php

namespace App\Entity\Satisfaction;

use App\Entity\Formulaire;
use App\Entity\TempFormulaire;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Satisfaction\BlockRepository")
 */
class Block
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Formulaire", inversedBy="blocks", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $formulaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Satisfaction\Question", mappedBy="block", orphanRemoval=true, cascade={"persist"})
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TempFormulaire", inversedBy="blocks", cascade={"persist"})
     */
    private $tempformulaire;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setBlock($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getBlock() === $this) {
                $question->setBlock(null);
            }
        }

        return $this;
    }

    public function getTempformulaire(): ?TempFormulaire
    {
        return $this->tempformulaire;
    }

    public function setTempformulaire(?TempFormulaire $tempformulaire): self
    {
        $this->tempformulaire = $tempformulaire;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
