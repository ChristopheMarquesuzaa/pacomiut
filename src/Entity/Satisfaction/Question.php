<?php

namespace App\Entity\Satisfaction;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Satisfaction\QuestionRepository")
 */
class Question
{
    const TYPE_SELECT_UNIQUE = 0;
    const TYPE_SELECT_MULTIPE = 1;
    const TYPE_CHECKBOX = 2;
    const TYPE_RADIO_BUTTON = 3;
    const TYPE_INPUT = 4;
    const TYPE_TEXTAREA = 5;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Satisfaction\Block", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $block;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answer;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Satisfaction\Result", mappedBy="question", orphanRemoval=true)
     */
    private $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getBlock(): ?Block
    {
        return $this->block;
    }

    public function setBlock(?Block $block): self
    {
        $this->block = $block;

        return $this;
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

    public function getTypeFormatted(): ?string
    {
        switch ($this->type) {
            case self::TYPE_SELECT_UNIQUE:
                return 'Liste à choix unique';
                break;
            case self::TYPE_SELECT_MULTIPE:
                return 'Liste à choix multiple';
                break;
            case self::TYPE_CHECKBOX:
                return 'Checkbox';
                break;
            case self::TYPE_RADIO_BUTTON:
                return 'Radio-button';
                break;
            case self::TYPE_INPUT:
                return 'Zone de text courte';
                break;
            case self::TYPE_TEXTAREA:
                return 'Zone de text longue';
                break;
            default:
                return 'Inconnu';
        }
    }

    public function isComplex(): bool
    {
        if ($this->getType() == self::TYPE_INPUT) {
            return false;
        }
        if ($this->getType() == self::TYPE_TEXTAREA) {
            return false;
        }

        return true;
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
            $result->setQuestion($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getQuestion() === $this) {
                $result->setQuestion(null);
            }
        }

        return $this;
    }
}
