<?php

namespace App\Entity;

use App\Entity\Submission;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SubmissionTask
{

    protected $submission;
    protected $operations;
    protected $projects;
    protected $miscellaneous;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
        $this->operations = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->miscellaneous = new ArrayCollection();
    }

    public function removeOperation(Operation $operation) {
        $this->operations->removeElement($operation);
    }

    public function removeProject(Project $project) {
        $this->projects->removeElement($project);
    }

    public function removeMiscellaneous(Miscellaneous $misc) {
        $this->miscellaneous->removeElement($misc);
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

    public function getMiscellaneous(): Collection {
        return $this->miscellaneous;
    }

}