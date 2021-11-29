<?php

namespace App\Controller\Evaluation;


use App\Entity\Submission\Sections\ProjectEntry;
use App\Entity\Submission\Submission;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_MANAGER")
 */
class EvaluationController extends AbstractController
{
    /**
     * @Route("/evaluation", name="submission_evaluation")
     */
    public function evaluationOverview(EntityManagerInterface $entityManager): Response
    {
        $submissions = $entityManager->createQueryBuilder()
            ->select('s.SubmissionMonth')
            ->from(Submission::class, 's')
            ->groupBy('s.SubmissionMonth')
            ->getQuery()
            ->getResult();

        $submissionsSubMonth = array_map(function (array $s) {
            return $s['SubmissionMonth'];
        }, $submissions);
        rsort($submissionsSubMonth);

        $subsYearMonth = [];

        foreach ($submissionsSubMonth as $submitted) {
            if ($submitted instanceof DateTimeInterface) {
                $year = $submitted->format('Y');
                if (array_key_exists($year, $subsYearMonth)) {
                    $subsYearMonth[$year][] = $submitted;
                } else {
                    $subsYearMonth[$year] = [$submitted];
                }
            }
        }

        foreach ($subsYearMonth as $year => &$months) {
            usort($months, function (DateTime $a, DateTime $b) {
                $diff = $a <=> $b;
                return $diff * (-1);
            });
        }

        return $this->render('evaluation/index.html.twig', [
            'subsYearMonth' => $subsYearMonth
        ]);
    }

    /**
     * @Route("/evaluation/month", name="submission_evaluation_month")
     */
    public function evaluationMonth(Request $request, EntityManagerInterface $entityManager): Response
    {

        if ($request->isXmlHttpRequest()) {

            $year = $request->request->get('year');
            $month = $request->request->get('month');
            $subMonth = DateTIme::createFromFormat('j-m-Y', '01-' . $month . '-' . $year);

            $submissions = $entityManager->getRepository(Submission::class)->findBy([
                'SubmissionMonth' => $subMonth
            ]);


            ### Evaluation
            // $projectNames = [];
            // $usersProject = [];
            // foreach ($submissions as $submission) {
            //     if ($submission instanceof Submission) {
            //         $project = $submission->getProjects();
            //         $project->map(function(Project $project) use (&$projectNames, &$usersProject) {
            //             $projectNames[$project->getName()] = [];
            //             $usersProject[$project->getSubmissionId()->getUserId()] = [];
            //         });
            //     }
            // }

            // foreach ($usersProject as $userProjects) {
            //     $userProjects[] = $projectNames;
            // }

            $projects = [];
            foreach ($submissions as $submission) {
                if ($submission instanceof Submission) {
                    $project = $submission->getProjects();
                    $project->map(function(ProjectEntry $project) use (&$projects) {
                        $projectName = $project->getName();
                        if (array_key_exists($projectName, $projects)) {
                            $projects[$projectName][] = [
                                'user' => $project->getSubmission()->getUser()->getName(),
                                'actualHours' => $project->getActualHours() ?? 'NA',
                                'targetHours' => $project->getTargetHours(),
                            ];
                        } else {
                            $projects[$projectName][] = [
                                'user' => $project->getSubmission()->getUser()->getName(),
                                'actualHours' => $project->getActualHours() ?? 'NA',
                                'targetHours' => $project->getTargetHours(),
                            ];
                        }
                    });
                }
            }

            return new JsonResponse(['output' => $this->renderView('evaluation/_evalMonth.html.twig', [
                'submissions' => $submissions,
                'subMonth' => $subMonth,
                'projects' => $projects,
            ])]);
        }

        return $this->redirectToRoute('home');
    }
}
