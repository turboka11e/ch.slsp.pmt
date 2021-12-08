<?php

namespace App\Entity\Submission\Sections;

use App\Entity\Submission\Submission;
use App\Repository\MiscellaneousEntryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MiscellaneousEntryRepository::class)
 */
class MiscellaneousEntry
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
    private $Task;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="float")
     */
    private $TargetHours;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Comment;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Submission\Submission::class, inversedBy="miscellaneousEntries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Submission;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?string
    {
        return $this->Task;
    }

    public function setTask(string $Task): self
    {
        $this->Task = $Task;

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

    public function getTargetHours(): ?float
    {
        return $this->TargetHours;
    }

    public function setTargetHours(float $TargetHours): self
    {
        $this->TargetHours = $TargetHours;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->Comment;
    }

    public function setComment(?string $Comment): self
    {
        $this->Comment = $Comment;

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
