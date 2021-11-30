<?php

namespace App\Entity;

use App\Entity\Submission\Sections\ProjectEntry;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
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
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $HoursSold;

    /**
     * @ORM\Column(type="date")
     */
    private $Created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Archive;

    /**
     * @ORM\OneToMany(targetEntity=ProjectEntry::class, mappedBy="project", orphanRemoval=true)
     * @OrderBy({"Submission" = "DESC"})
     */
    private $ProjectEntry;

    public function __construct()
    {
        $this->ProjectEntry = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getHoursSold(): ?int
    {
        return $this->HoursSold;
    }

    public function setHoursSold(int $HoursSold): self
    {
        $this->HoursSold = $HoursSold;

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

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->Archive;
    }

    public function setArchive(bool $Archive): self
    {
        $this->Archive = $Archive;

        return $this;
    }

    /**
     * @return Collection|ProjectEntry[]
     */
    public function getProjectEntry(): Collection
    {
        return $this->ProjectEntry;
    }

    /**
     * @return void
     */
    public function sortProjectEntriesByTime()
    {
        $iter = $this->ProjectEntry->getIterator();
        $iter->uasort(function (ProjectEntry $a, ProjectEntry $b) {
            return ($a->getSubmission()->getSubmissionMonth() <=> $b->getSubmission()->getSubmissionMonth()) * (-1);
        });
        $this->ProjectEntry = new ArrayCollection(iterator_to_array($iter));
    }

    public function addProjectEntry(ProjectEntry $projectEntry): self
    {
        if (!$this->ProjectEntry->contains($projectEntry)) {
            $this->ProjectEntry[] = $projectEntry;
            $projectEntry->setProject($this);
        }

        return $this;
    }

    public function removeProjectEntry(ProjectEntry $projectEntry): self
    {
        if ($this->ProjectEntry->removeElement($projectEntry)) {
            // set the owning side to null (unless already changed)
            if ($projectEntry->getProject() === $this) {
                $projectEntry->setProject(null);
            }
        }

        return $this;
    }
}
