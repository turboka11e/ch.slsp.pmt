<?php

namespace App\Controller;

use App\Entity\CategoryChoice;
use App\Entity\ProjectChoice;
use App\Entity\Submission;
use App\Entity\SubmissionTask;
use App\Form\SubmissionTaskFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_USER")
 */
class SubmissionController extends AbstractController
{
    /**
     * @Route("/submission", name="submission")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $submission = new Submission();
        $submission->setUserId($this->getUser());
        $submission->setFormType('WorkingHours');

        $today = new DateTime('now');
        $nextMonth = new DateTime('first day of next month');

        $submission->setCreated($today);
        $submission->setUpdated($today);
        $submission->setSubmissionMonth($nextMonth);


        $task = new SubmissionTask($submission);

        $form = $this->createForm(SubmissionTaskFormType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 
                'Form was successfully saved!'
            );
            $submission = $task->getSubmission();
            $entityManager->persist($submission);

            foreach ($task->getOperations() as $op) {
                $op->setSubmissionId($submission);
                $entityManager->persist($op);
            }
            foreach ($task->getProjects() as $project) {
                $project->setSubmissionId($submission);
                $entityManager->persist($project);
            }
            foreach ($task->getMiscellaneous() as $misc) {
                $misc->setSubmissionId($submission);
                $entityManager->persist($misc);
            }

            $entityManager->flush();

        }

        return $this->render('submission/index.html.twig', [
            'form' => $form->createView(),
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'form'
        ]);
    }
}
