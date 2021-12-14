<?php

namespace App\Controller\Evaluation;

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
use Svrnm\ExcelDataTables\ExcelDataTable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @IsGranted("ROLE_MANAGER")
 * @Route("/evaluation")
 */
class EvaluationController extends AbstractController
{
    /**
     * @Route("/", name="submission_evaluation")
     */
    public function evaluationOverview(EntityManagerInterface $entityManager): Response
    {

        // Prepare Timeline choices
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
        // End Timeline choices

        $sql = 'SELECT
                    p.name as "Name",
                    p.hours_sold as "hours_sold",
                    count(DISTINCT (u.id)) as "submitter",
                    sum(pe.target_hours) AS "targetHours",
                    sum(pe.actual_hours) AS "actualHours",
                    p.hours_sold - sum(pe.actual_hours) AS "diff"
                FROM
                    project_entry pe,
                    user u,
                    submission s,
                    project p
                WHERE 1 = 1
                    AND pe.submission_id = s.id
                    AND pe.project_id = p.id
                    AND s.user_id = u.id
                GROUP BY
                    p.name, p.hours_sold
                ;';

        $conn = $entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $allTimeProjects = $resultSet->fetchAllAssociative();

        return $this->render('evaluation/index.html.twig', [
            'subsYearMonth' => $subsYearMonth,
            'all_time_projects' => $allTimeProjects
        ]);
    }

    /**
     * @Route("/month", name="submission_evaluation_month")
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

            return new JsonResponse(['output' => $this->renderView('evaluation/_evalMonth.html.twig', [
                'submissions' => $submissions,
                'sub_month' => $subMonth
            ])]);
        }

        return $this->redirectToRoute('submission_evaluation');
    }

    /**
     * @Route("/download/projects/all", name="download_all_projects")
     */
    public function evaluationAllProjects(EntityManagerInterface $em): BinaryFileResponse
    {
        $sql = 'SELECT
                    p.name as "Project Name",
                    p.status as "Project Status",
                    YEAR(s.submission_month) as "Year",
                    MONTHNAME(s.submission_month) as "Month",
                    p.hours_sold as "Hours Sold",
                    CONCAT(u.name, CONCAT(" ", u.surname)) as "Name long",
                    CONCAT(SUBSTRING(u.name, 1, 1), SUBSTRING(u.surname, 1, 2)) as "Name short",
                    pe.target_hours as "Target Hours", 
                    pe.actual_hours as "Actual Hours",
                    p.target_hours - pe.actual_hours as "Diff",
                    pe.status as "Individual Status",
                    pe.priority as "Priority",
                    pe.work_results as "Work Results"
                FROM
                    `user` u ,
                    submission s ,
                    project_entry pe ,
                    project p
                WHERE
                    1 = 1
                    AND u.id = s.user_id
                    AND s.id = pe.submission_id
                    AND pe.project_id = p.id
                order by
                    p.name 
                ;';

        $conn = $em->getConnection();
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        $projectTableData = $resultSet->fetchAllAssociative();

        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';
        $templateFilepath =  $publicDirectory . '/evaluations/allTimeTemplate.xlsx';

        $dataTable = new ExcelDataTable();
        $in = $templateFilepath;

        $user = $this->getUser();
        $out = $publicDirectory . '/evaluations/' . $user->getNameShort() . 'tmp.xlsx';

        $dataTable->showHeaders()->addRows($projectTableData)->attachToFile($in, $out);

        $today = new DateTime('now');

        $response = new BinaryFileResponse($out);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'allProjects' . $today->format('YMD') . '.xlsx'
        );
        return $response;
    }
}
