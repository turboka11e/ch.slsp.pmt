<?php

namespace App\Controller;

use App\Entity\Miscellaneous;
use App\Entity\Operation;
use App\Entity\Project;
use App\Entity\Submission;
use App\Entity\SubmissionTask;
use App\Entity\User;
use App\Form\SubmissionTaskFormType;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Util\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    public function submissions(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $year = $request->request->get('year');
            $month = $request->request->get('month');
    
            /// TEST

            $user = $this->getUser();

            if (is_null($year) || is_null($month)) {
                $this->addFlash(
                    'danger',
                    'Bad Request'
                );
                return $this->redirectToRoute('home');
            }
    
            $subMonth = DateTIme::createFromFormat('j-m-Y', '01-' . $month . '-' . $year);
    
            $submission = $this->getDoctrine()->getRepository(Submission::class)->findOneBy([
                'SubmissionMonth' => $subMonth,
                'UserId' => $user->getId()
            ]);
    
            if (is_null($submission)) {
                $this->addFlash(
                    'error',
                    'Form not available for ' . $subMonth->format('F')
                );
                return $this->redirectToRoute('home');
            }
    
            $today = new DateTime('now');
    
            $submission->setUpdated($today);
    
            $task = new SubmissionTask($submission);
    
            $operations = $this->getDoctrine()->getRepository(Operation::class)->findBy([
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
            
            $form = $this->createForm(SubmissionTaskFormType::class, $task);
            $form->remove('Submit');
            $test = ['output' => $this->renderView('submission/show.template.html.twig', [
                'today' => $today,
                'subMonth' => $subMonth,
                'form' => $form->createView(),
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                'csrf_token_id' => 'form'
            ])];
            return new JsonResponse($test);
        }

        return $this->render('submissions/index.html.twig', []);
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
