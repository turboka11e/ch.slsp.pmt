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
    private $operations;

    /**
     * @ORM\OneToMany(targetEntity=App\Entity\Submission\Sections\ProjectEntry::class, mappedBy="Submission", orphanRemoval=true, cascade={"persist"})
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=App\Entity\Submission\Sections\MiscellaneousEntry::class, mappedBy="Submission", orphanRemoval=true, cascade={"persist"})
     */
    private $miscellaneouses;

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
        $this->operations = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->miscellaneouses = new ArrayCollection();
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
     * @return Collection|Operation[]
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(OperationEntry $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setSubmission($this);
        }

        return $this;
    }

    public function removeOperation(OperationEntry $operation): self
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getSubmission() === $this) {
                $operation->setSubmission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(ProjectEntry $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setSubmission($this);
        }

        return $this;
    }

    public function removeProject(ProjectEntry $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getSubmission() === $this) {
                $project->setSubmission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Miscellaneous[]
     */
    public function getMiscellaneouses(): Collection
    {
        return $this->miscellaneouses;
    }

    public function addMiscellaneouse(MiscellaneousEntry $miscellaneouse): self
    {
        if (!$this->miscellaneouses->contains($miscellaneouse)) {
            $this->miscellaneouses[] = $miscellaneouse;
            $miscellaneouse->setSubmission($this);
        }

        return $this;
    }

    public function removeMiscellaneouse(MiscellaneousEntry $miscellaneouse): self
    {
        if ($this->miscellaneouses->removeElement($miscellaneouse)) {
            // set the owning side to null (unless already changed)
            if ($miscellaneouse->getSubmission() === $this) {
                $miscellaneouse->setSubmission(null);
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
