<?php

namespace App\Controller\Evaluation;

use App\Entity\Submission\Submission;
use App\Entity\User;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvaluationController extends AbstractController
{
    /**
     * @Route("/evaluation", name="submission_evaluation")
     */
    public function evaluationOverview(EntityManagerInterface $entityManager): Response
    {

        // $opIds = $operations->map(function (Operation $o) {
        //     echo "test " . $o->getId();
        //     return $o->getId();
        // });
        // $opIds = $opIds->toArray();
        // echo strval($opIds[0]);
        // $qb->delete(Operation::class, 'o')
        //     ->andWhere($qb->expr()->notIn('o.id', $opIds))
        //     ->andWhere($qb->expr()->eq('o.submission', $task->getSubmission()->getId()));

        // echo sizeof($opIds);
        // $qb->getQuery()->execute()

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

            $qb = $entityManager->createQueryBuilder();
            $qb->select('u.name AS Name, u.surname AS Surname, s.SubmissionMonth')
                ->from(Submission::class, 's')
                ->from(User::class, 'u')
                ->andWhere($qb->expr()->eq('u.id', 's.UserId'))
                ->andWhere($qb->expr()->eq('s.SubmissionMonth', ':date'))
                ->groupBy('s.SubmissionMonth, u.name, u.surname')
                ->setParameter('date', $subMonth->format("Y-m-d") . " 00:00:00");

            $submissions = $qb->getQuery()->getResult();

            return new JsonResponse(['output' => $this->renderView('evaluation/_evalMonth.html.twig', [
                'submissions' => $submissions,
                'subMonth' => $subMonth,
            ])]);
        }

        return $this->redirectToRoute('home');
    }
}
