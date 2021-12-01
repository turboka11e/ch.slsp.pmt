<?php

namespace App\Controller\Evaluation;

use App\Entity\Project;
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

        $projects = $entityManager->getRepository(Project::class)->findBy([
            'Archive' => false
        ]);

        foreach ($projects as $project) {
            if ($project instanceof Project) {
                $project->sortProjectEntriesByTime();
            }
        }

        return $this->render('evaluation/index.html.twig', [
            'subsYearMonth' => $subsYearMonth,
            'projects' => $projects
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
            $subMonth = DateTime::createFromFormat('j-m-Y', '01-' . $month . '-' . $year);

            $submissions = $entityManager->getRepository(Submission::class)->findBy([
                'SubmissionMonth' => $subMonth
            ]);

            // Sort Submissions by Name
            uasort($submissions, function (Submission $a, Submission $b) {
                return $a->getUser()->getName() <=> $b->getUser()->getName();
            });

            ### Evaluation
            $projectNames = [];
            $userNames = [];
            // Prepare Lists as template for evaluation
            foreach ($submissions as $submission) {
                if ($submission instanceof Submission) {
                    // Save Username in List
                    $userNames[$submission->getUser()->getNameShort()] = [];
                    // Save Projectname in List
                    $project = $submission->getProjectEntries();
                    $project->map(function (ProjectEntry $project) use (&$projectNames) {
                        $projectNames[$project->getProject()->getName()] = [];
                    });
                }
            }
            // Sort projectNames by Name
            ksort($projectNames);
            // Add Total Object at the end
            $userNames["Total"] = [
                'actualHours' => 0.0,
                'targetHours' => 0.0,
                'diff' => 0.0,
            ];
            // Put every name to each project
            array_walk($projectNames, function (&$value, $key) use ($userNames) {
                $value = $userNames;
            });

            // Populate projectNames with values from submissions
            array_walk($submissions, function (Submission &$submission) use (&$projectNames) {
                $projects = $submission->getProjectEntries();
                $username = $submission->getUser()->getNameShort();
                $projectsArray = $projects->toArray();
                // Iterate over users projects and put values in projectsNames
                array_walk($projectsArray, function (ProjectEntry &$projectEntry, $key) use (&$projectNames, $username) {
                    // Dont need to check whether project is in list because it has to be
                    $actualHours = $projectEntry->getActualHours();
                    $targetHours = $projectEntry->getTargetHours();
                    $diff = $targetHours - $actualHours ?? 0;
                    $projectNames[$projectEntry->getProject()->getName()][$username] = [
                        'actualHours' =>  $actualHours ?? 'NA',
                        'targetHours' => $targetHours,
                        'diff' => $diff,
                    ];
                    $projectNames[$projectEntry->getProject()->getName()]["Total"]["actualHours"] += $actualHours ?? 0;
                    $projectNames[$projectEntry->getProject()->getName()]["Total"]["targetHours"] += $targetHours;
                    $projectNames[$projectEntry->getProject()->getName()]["Total"]["diff"] += $diff;
                });
            });

            return new JsonResponse(['output' => $this->renderView('evaluation/_evalMonth.html.twig', [
                'submissions' => $submissions,
                'sub_month' => $subMonth,
                'project_names' => $projectNames,
                'user_names' => $userNames,
            ])]);
        }

        return $this->redirectToRoute('home');
    }
}
