<?php

namespace App\Controller;

use App\Entity\Miscellaneous;
use App\Entity\Operation;
use App\Entity\Project;
use App\Entity\Submission;
use App\Entity\SubmissionTask;
use App\Entity\User;
use App\Form\SubmissionTaskFormType;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use function PHPUnit\Framework\containsEqual;
use function PHPUnit\Framework\isNull;

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
        if ($request->isXmlHttpRequest()) {

            $results = $this->getYearMonthTodaySubMonth($request);
            if (is_null($results)) {
                return new JsonResponse(['output' => $this->renderView('submissions/_error.html.twig')]);
            }
            [$year, $month, $today, $subMonth] = $results;

            $user = $this->getUser();
            $task = $this->getSubmission($entityManager, $subMonth, $today, $user);
            if (is_null($task)) {
                return new JsonResponse(['output' => $this->renderView('submissions/_error.html.twig')]);
            }

            $form = $this->createForm(SubmissionTaskFormType::class, $task);
            $form->remove('Submit');

            return new JsonResponse(['output' => $this->renderView('submissions/_readonly.html.twig', [
                'today' => $today,
                'subMonth' => $subMonth,
                'year' => $year,
                'month' => $month,
                'form' => $form->createView(),
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                'csrf_token_id' => 'form'
            ])]);
        }

        $submissions = $entityManager->getRepository(Submission::class)->findBy([
            'UserId' => $this->getUser()->getId(),
        ]);

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
                'years' => range(date('Y'), date('Y') + 10)
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

    public function getSubmission(EntityManager $entityManager, $subMonth, $today, $user): ?SubmissionTask
    {
        $submission = $entityManager->getRepository(Submission::class)->findOneBy([
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

        $submission->setUpdated($today);

        $task = new SubmissionTask($submission);

        $operations = $entityManager->getRepository(Operation::class)->findBy([
            'SubmissionId' => $submission,
        ]);
        $task->setOperations(new ArrayCollection($operations));

        $projects = $this->getDoctrine()->getRepository(Project::class)->findBy([
            'SubmissionId' => $submission,
        ]);
        $task->setProjects(new ArrayCollection($projects));

        $miscs = $this->getDoctrine()->getRepository(Miscellaneous::class)->findBy([
            'SubmissionId' => $submission,
        ]);
        $task->setMiscellaneouses(new ArrayCollection($miscs));
        return $task;
    }

    /**
     * @Route("/profile/submissions/download", name="app_download_user_year_month")
     */
    public function userDownload(Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->get('user');
        $month = $request->get('month');
        $year = $request->get('year');
        $fs = new Filesystem();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("My First Worksheet");


        $qb = $em->createQueryBuilder()
            ->select('u.surname')
            ->from(User::class, 'u')
            ->where('1=1')
            ->orderBy('u.surname', 'ASC');

        $query = $qb->getQuery();
        // $result = $query->execute();

        $name = $query->execute();

        $sheet->setCellValue('A1', $name[1]['surname']);



        // Optimal Width
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';

        // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
        $excelFilepath =  $publicDirectory . '/my_first_excel_symfony4.xlsx';

        // Create the file
        $writer->save($excelFilepath);

        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';
        // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
        $excelFilepath =  $publicDirectory . '/my_first_excel_symfony4.xlsx';

        $response = new BinaryFileResponse($excelFilepath);
        return $response;
    }
}
