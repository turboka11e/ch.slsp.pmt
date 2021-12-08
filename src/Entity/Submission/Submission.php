<?php

namespace App\Entity\Submission;

use App\Entity\Submission\Sections\MiscellaneousEntry;
use App\Entity\User;
use App\Repository\SubmissionRepository;
use App\Entity\Submission\Sections\OperationEntry;
use App\Entity\Submission\Sections\ProjectEntry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubmissionRepository::class)
 */
class Submission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="submissions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="date")
     */
    private $Created;

    /**
     * @ORM\Column(type="date")
     */
    private $Updated;

    /**
     * @ORM\Column(type="date")
     */
    private $SubmissionMonth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FormType;

    /**
     * @ORM\Column(type="float")
     */
    private $Workdays;

    /**
     * @ORM\OneToMany(targetEntity=App\Entity\Submission\Sections\OperationEntry::class, mappedBy="Submission", orphanRemoval=true, cascade={"persist"})
     */
    private $operationEntries;

    /**
     * @ORM\OneToMany(targetEntity=App\Entity\Submission\Sections\ProjectEntry::class, mappedBy="Submission", orphanRemoval=true, cascade={"persist"})
     */
    private $projectEntries;

    /**
     * @ORM\OneToMany(targetEntity=App\Entity\Submission\Sections\MiscellaneousEntry::class, mappedBy="Submission", orphanRemoval=true, cascade={"persist"})
     */
    private $miscellaneousEntries;

    /**
     * @ORM\Column(type="float")
     */
    private $PlannedAbsences;

    /**
     * @ORM\Column(type="float")
     */
    private $FurtherAbsences;

    public function __construct()
    {
        $this->FurtherAbsences = 0;
        $this->PlannedAbsences = 0;
        $this->operationEntries = new ArrayCollection();
        $this->projectEntries = new ArrayCollection();
        $this->miscellaneousEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->Created;
    }

    public function setCreated(\DateTimeInterface $Created): self
    {
        $this->Created = $Created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->Updated;
    }

    public function setUpdated(\DateTimeInterface $Updated): self
    {
        $this->Updated = $Updated;

        return $this;
    }

    public function getSubmissionMonth(): ?\DateTimeInterface
    {
        return $this->SubmissionMonth;
    }

    public function setSubmissionMonth(\DateTimeInterface $SubmissionMonth): self
    {
        $this->SubmissionMonth = $SubmissionMonth;

        return $this;
    }

    public function getFormType(): ?string
    {
        return $this->FormType;
    }

    public function setFormType(string $FormType): self
    {
        $this->FormType = $FormType;

        return $this;
    }

    public function getWorkdays(): ?float
    {
        return $this->Workdays;
    }

    public function setWorkdays(float $Workdays): self
    {
        $this->Workdays = $Workdays;

        return $this;
    }

    /**
     * @return Collection|OperationEntry[]
     */
    public function getOperationEntries(): Collection
    {
        return $this->operationEntries;
    }

    public function addOperationEntry(OperationEntry $operationEntry): self
    {
        if (!$this->operationEntries->contains($operationEntry)) {
            $this->operationEntries[] = $operationEntry;
            $operationEntry->setSubmission($this);
        }

        return $this;
    }

    public function removeOperationEntry(OperationEntry $operationEntry): self
    {
        if ($this->operationEntries->removeElement($operationEntry)) {
            // set the owning side to null (unless already changed)
            if ($operationEntry->getSubmission() === $this) {
                $operationEntry->setSubmission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProjectEntry[]
     */
    public function getProjectEntries(): Collection
    {
        return $this->projectEntries;
    }

    public function addProjectEntry(ProjectEntry $projectEntry): self
    {
        if (!$this->projectEntries->contains($projectEntry)) {
            $this->projectEntries[] = $projectEntry;
            $projectEntry->setSubmission($this);
        }

        return $this;
    }

    public function removeProjectEntry(ProjectEntry $projectEntry): self
    {
        if ($this->projectEntries->removeElement($projectEntry)) {
            // set the owning side to null (unless already changed)
            if ($projectEntry->getSubmission() === $this) {
                $projectEntry->setSubmission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MiscellaneousEntries[]
     */
    public function getMiscellaneousEntries(): Collection
    {
        return $this->miscellaneousEntries;
    }

    public function addMiscellaneousEntry(MiscellaneousEntry $miscellaneousEntry): self
    {
        if (!$this->miscellaneousEntries->contains($miscellaneousEntry)) {
            $this->miscellaneousEntries[] = $miscellaneousEntry;
            $miscellaneousEntry->setSubmission($this);
        }

        return $this;
    }

    public function removeMiscellaneousEntry(MiscellaneousEntry $miscellaneousEntry): self
    {
        if ($this->miscellaneousEntries->removeElement($miscellaneousEntry)) {
            // set the owning side to null (unless already changed)
            if ($miscellaneousEntry->getSubmission() === $this) {
                $miscellaneousEntry->setSubmission(null);
            }
        }

        return $this;
    }

    public function getPlannedAbsences(): ?float
    {
        return $this->PlannedAbsences;
    }

    public function setPlannedAbsences(float $PlannedAbsences): self
    {
        $this->PlannedAbsences = $PlannedAbsences;

        return $this;
    }

    public function getFurtherAbsences(): ?float
    {
        return $this->FurtherAbsences;
    }

    public function setFurtherAbsences(float $FurtherAbsences): self
    {
        $this->FurtherAbsences = $FurtherAbsences;

        return $this;
    }
}
