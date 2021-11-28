<?php

namespace App\Repository;

use App\Entity\Submission\Sections\Miscellaneous;
use App\Entity\Submission\Sections\Operation;
use App\Entity\Submission\Sections\Project;
use App\Entity\Submission\Submission;
use App\Entity\SubmissionTask;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Submission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Submission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Submission[]    findAll()
 * @method Submission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Submission::class);
    }

    /**
     * @return SubmissionTask|null Returns an SubmissionTask
     */
    public function findSubmissionTask(DateTime $subMonth, User $user): ?SubmissionTask
    {
        $submission = $this->findOneBy([
            'SubmissionMonth' => $subMonth,
            'UserId' => $user->getId()
        ]);

        if (is_null($submission)) {
            $this->addFlash(
                'error',
                'Form not available for ' . $subMonth->format('F')
            );
            return null;
        }

        $task = new SubmissionTask($submission);
        
        $operations = $this->getEntityManager()->getRepository(Operation::class)->findBy([
            'SubmissionId' => $submission,
        ]);
        $task->setOperations(new ArrayCollection($operations));

        $projects = $this->getEntityManager()->getRepository(Project::class)->findBy([
            'SubmissionId' => $submission,
        ]);
        $task->setProjects(new ArrayCollection($projects));

        $miscs = $this->getEntityManager()->getRepository(Miscellaneous::class)->findBy([
            'SubmissionId' => $submission,
        ]);
        $task->setMiscellaneouses(new ArrayCollection($miscs));
        return $task;
    }

    /**
     * @return SubmissionTask[]|null Returns an SubmissionTask
     */
    public function findAllSubmissionTasks(DateTime $subMonth)
    {
        $qb = $this->createQueryBuilder('o');

    }
}
