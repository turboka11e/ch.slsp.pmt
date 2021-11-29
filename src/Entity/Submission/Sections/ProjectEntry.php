<?php

namespace App\Entity\Submission\Sections;

use App\Entity\Project;
use App\Entity\Submission\Submission;
use App\Repository\ProjectEntryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectEntryRepository::class)
 */
class ProjectEntry
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
    private $Description;

    /**
     * @ORM\Column(type="float")
     */
    private $TargetHours;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ActualHours;

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
     * @ORM\ManyToOne(targetEntity=App\Entity\Submission\Submission::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Submission;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="ProjectEntry")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTargetHours(): ?float
    {
        return $this->TargetHours;
    }

    public function setTargetHours(float $TargetHours): self
    {
        $this->TargetHours = $TargetHours;

        return $this;
    }

    public function getActualHours(): ?float
    {
        return $this->ActualHours;
    }

    public function setActualHours(float $ActualHours): self
    {
        $this->ActualHours = $ActualHours;

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
