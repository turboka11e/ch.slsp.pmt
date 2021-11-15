<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Util\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_MANAGER")
 */
class DownloadController extends AbstractController
{

    /**
     * @Route("/manager/downloads/home", name="app_downloads")
     */
    public function FunctionName(): Response
    {
        return $this->render('$0.html.twig', []);
    }

    /**
     * @Route("/manager/downloads/", name="app_download_user_year_month")
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
