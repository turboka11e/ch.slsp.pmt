<?php

namespace App\Entity;

use App\Entity\Submission\Sections\Miscellaneous;
use App\Entity\Submission\Sections\Operation;
use App\Entity\Submission\Sections\Project;
use App\Entity\Submission\Submission;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SubmissionTask
{

    protected $submission;
    protected $operations;
    protected $projects;
    protected $miscellaneouses;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
        $this->operations = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->miscellaneouses = new ArrayCollection();
    }

    public function addOperation(Operation $operation) {
        $this->operations->add($operation);
    }

    public function addProject(Project $project) {
        $this->projects->add($project);
    }

    public function addMiscellaneous(Miscellaneous $misc) {
        $this->miscellaneouses->add($misc);
    }

    public function removeOperation(Operation $operation) {
        $this->operations->removeElement($operation);
    }

    public function removeProject(Project $project) {
        $this->projects->removeElement($project);
    }

    public function removeMiscellaneous(Miscellaneous $misc) {
        $this->miscellaneouses->removeElement($misc);
    }

    public function getSubmission(): ?Submission {
        return $this->submission;
    }

    public function getOperations(): Collection {
        return $this->operations;
    }

    public function getProjects(): Collection {
        return $this->projects;
    }

    public function getMiscellaneouses(): Collection {
        return $this->miscellaneouses;
    }

    public function setOperations(Collection $operations): void {
        $this->operations = $operations;
    }

    public function setProjects(Collection $projects): void {
        $this->projects = $projects;
    }

    public function setMiscellaneouses(Collection $misc): void {
        $this->miscellaneouses = $misc;
    }

}