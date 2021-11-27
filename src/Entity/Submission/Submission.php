<?php

namespace App\Entity\Submission;

use App\Entity\User;
use App\Entity\Submission\Sections\Miscellaneous;
use App\Entity\Submission\Sections\Operation;
use App\Entity\Submission\Sections\Project;
use App\Repository\SubmissionRepository;
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
    private $UserId;

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
     * @ORM\OneToMany(targetEntity=App\Entity\Submission\Sections\Operation::class, mappedBy="SubmissionId", orphanRemoval=true)
     */
    private $operations;

    /**
     * @ORM\OneToMany(targetEntity=App\Entity\Submission\Sections\Project::class, mappedBy="SubmissionId", orphanRemoval=true)
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=App\Entity\Submission\Sections\Miscellaneous::class, mappedBy="SubmissionId", orphanRemoval=true)
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

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): self
    {
        $this->UserId = $UserId;

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

    public function addOperation(Operation $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setSubmissionId($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getSubmissionId() === $this) {
                $operation->setSubmissionId(null);
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

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setSubmissionId($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getSubmissionId() === $this) {
                $project->setSubmissionId(null);
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

    public function addMiscellaneouse(Miscellaneous $miscellaneouse): self
    {
        if (!$this->miscellaneouses->contains($miscellaneouse)) {
            $this->miscellaneouses[] = $miscellaneouse;
            $miscellaneouse->setSubmissionId($this);
        }

        return $this;
    }

    public function removeMiscellaneouse(Miscellaneous $miscellaneouse): self
    {
        if ($this->miscellaneouses->removeElement($miscellaneouse)) {
            // set the owning side to null (unless already changed)
            if ($miscellaneouse->getSubmissionId() === $this) {
                $miscellaneouse->setSubmissionId(null);
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
