<?php

namespace App\Entity;

use App\Repository\ProjectChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectChoiceRepository::class)
 */
class ProjectChoice
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
    private $Project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?string
    {
        return $this->Project;
    }

    public function setProject(string $Project): self
    {
        $this->Project = $Project;

        return $this;
    }

}
