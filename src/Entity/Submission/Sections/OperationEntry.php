<?php

namespace App\Entity\Submission\Sections;

use App\Entity\Submission\Submission;
use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationEntryRepository::class)
 */
class OperationEntry
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
    private $Category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="float")
     */
    private $Hours;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Priority;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $WorkResults;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Status;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Submission\Submission::class, inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Submission;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->Category;
    }

    public function setCategory(string $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getHours(): ?float
    {
        return $this->Hours;
    }

    public function setHours(float $Hours): self
    {
        $this->Hours = $Hours;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->Priority;
    }

    public function setPriority(string $Priority): self
    {
        $this->Priority = $Priority;

        return $this;
    }

    public function getWorkResults(): ?string
    {
        return $this->WorkResults;
    }

    public function setWorkResults(?string $WorkResults): self
    {
        $this->WorkResults = $WorkResults;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getSubmission(): ?Submission
    {
        return $this->Submission;
    }

    public function setSubmission(?Submission $Submission): self
    {
        $this->Submission = $Submission;

        return $this;
    }
}
