<?php

namespace App\Controller\Profile;

use App\Entity\Submission\Submission;
use App\Form\Submission\SubmissionFormType;
use App\Entity\User;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Util\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_USER")
 */
class SubmissionsController extends AbstractController
{

    /**
     * @Route("/profile/submissions", name="app_submissions")
     */
    public function submissions(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($request->isXmlHttpRequest()) {

            $results = $this->getYearMonthTodaySubMonth($request);
            if (is_null($results)) {
                return new JsonResponse(['output' => $this->renderView('submissions/_error.html.twig')]);
            }
            [$year, $month, $today, $subMonth] = $results;

            $submission = $entityManager->getRepository(Submission::class)->findOneBy([
                'SubmissionMonth' => $subMonth,
                'User' => $user->getId()
            ]);

            if (is_null($submission)) {
                return new JsonResponse(['output' => $this->renderView('submissions/_error.html.twig')]);
            }

            return new JsonResponse(['output' => $this->renderView('submission/_ajax.readonly.html.twig', [
                'today' => $today,
                'subMonth' => $subMonth,
                'workload' => $user->getWorkload(),
                'year' => $year,
                'month' => $month,
                'submission' => $submission,
            ])]);
        }

        $submissions = $entityManager->getRepository(Submission::class)->findBy([
            'User' => $user->getId()
        ], ["SubmissionMonth" => 'desc']);

        $submissionsSubMonth = array_map(function (Submission $s) {
            return $s->getSubmissionMonth();
        }, $submissions);
        rsort($submissionsSubMonth);

        $subYearMonth = [];

        foreach ($submissionsSubMonth as $submitted) {
            if ($submitted instanceof DateTimeInterface) {
                $year = $submitted->format('Y');
                if (array_key_exists($year, $subYearMonth)) {
                    $subYearMonth[$year][] = $submitted;
                } else {
                    $subYearMonth[$year] = [$submitted];
                }
            }
        }

        foreach ($subYearMonth as $year => &$months) {
            usort($months, function (DateTime $a, DateTime $b) {
                $diff = $a <=> $b;
                return $diff * (-1);
            });
        }

        $defaultData = ['Date' => new DateTime('first day of next month')];
        $form = $this->createFormBuilder($defaultData)
            ->setAction($this->generateUrl('new_submission'))
            ->setMethod('GET')
            ->add('Date', DateType::class, [
                'years' => range(date('Y') - 1, date('Y') + 2)
            ])
            ->add('Create', SubmitType::class)
            ->getForm();

        return $this->render('submissions/index.html.twig', [
            'subYearMonth' => $subYearMonth,
            'createForm' => $form->createView()
        ]);
    }

    public function getYearMonthTodaySubMonth($request): array
    {
        $year = $request->request->get('year');
        $month = $request->request->get('month');
        $today = new DateTime('now');
        $subMonth = DateTIme::createFromFormat('j-m-Y', '01-' . $month . '-' . $year);
        $results = [$year, $month, $today, $subMonth];
        if (in_array(null, $results)) {
            $this->addFlash(
                'danger-subs',
                'Bad Request'
            );
        }
        return $results;
    }

    /**
     * @isGranted("ROLE_MANAGER")
     * @Route("/profile/extern/submission", name="extern_submission")
     */
    public function externSubmission(Request $request, EntityManagerInterface $entityManager): Response
    {

        if ($request->isXmlHttpRequest()) {

            $results = $this->getYearMonthTodaySubMonth($request);
            $user = $request->request->get('userId');
            [$year, $month, $today, $subMonth] = $results;
            $submission = $entityManager->getRepository(Submission::class)->findOneBy([
                'SubmissionMonth' => $subMonth,
                'User' => $user,
            ]);

            return new JsonResponse(['output' => $this->renderView('submission/modal/_ajax.content.html.twig', [
                'today' => $today,
                'subMonth' => $subMonth,
                'workload' => $submission->getUser()->getWorkload(),
                'year' => $year,
                'month' => $month,
                'submission' => $submission,
            ])]);
        }
    }
}
