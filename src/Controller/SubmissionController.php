<?php

namespace App\Controller;

use App\Entity\Miscellaneous;
use App\Entity\Operation;
use App\Entity\Project;
use App\Entity\ProjectChoice;
use App\Entity\Submission;
use App\Entity\SubmissionTask;
use App\Form\OperationFormType;
use App\Form\SubmissionTaskFormType;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_USER")
 */
class SubmissionController extends AbstractController
{
    /**
     * @Route("profile/submission/new", name="new_submission")
     */
    public function newSubmission(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
            
        $date = $request->get('form')['Date'];
        $year = $date['year'];
        $month = $date['month'];
  
        $subMonth = DateTIme::createFromFormat('j-m-Y', '01-' . $month . '-' . $year);

        $alreadyCreated = $this->getDoctrine()->getRepository(Submission::class)->findOneBy([
            'SubmissionMonth' => $subMonth,
            'UserId' => $user->getId()
        ]);

        if (!is_null($alreadyCreated)) {
            $this->addFlash(
                'error',
                'Form has already been submitted for ' . $subMonth->format('F')
            );
            return $this->redirectToRoute('app_submissions');
        }

        $today = new DateTime('now');

        $submission = new Submission();
        $submission->setUserId($user);
        $submission->setFormType('WorkingHours');

        $submission->setCreated($today);
        $submission->setUpdated($today);
        $submission->setSubmissionMonth($subMonth);

        $task = new SubmissionTask($submission);

        $form = $this->createForm(SubmissionTaskFormType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
            foreach ($task->getMiscellaneouses() as $misc) {
                $misc->setSubmissionId($submission);
                $entityManager->persist($misc);
            }

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Form was successfully saved!'
            );
            return $this->redirectToRoute('app_submissions');
        }

        return $this->render('submission/new.html.twig', [
            'today' => $today,
            'subMonth' => $subMonth,
            'form' => $form->createView(),
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'form'
        ]);
    }

    /**
     * @Route("profile/submission/edit", name="edit_submission")
     */
    public function editSubmission(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $year = $request->get('year');
        $month = $request->get('month');

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

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submission = $task->getSubmission();
            $entityManager->persist($submission);

            // Operations
            foreach ($operations as $original) {
                if (false === $task->getOperations()->contains($original)) {
                    $entityManager->remove($original);
                }
            }
            foreach ($task->getOperations() as $op) {
                $op->setSubmissionId($submission);
                $entityManager->persist($op);
            }

            // Projects
            foreach ($projects as $original) {
                if (false === $task->getProjects()->contains($original)) {
                    $entityManager->remove($original);
                }
            }
            foreach ($task->getProjects() as $project) {
                $project->setSubmissionId($submission);
                $entityManager->persist($project);
            }

            // Miscs
            foreach ($miscs as $original) {
                if (false === $task->getProjects()->contains($original)) {
                    $entityManager->remove($original);
                }
            }
            foreach ($task->getMiscellaneouses() as $misc) {
                $misc->setSubmissionId($submission);
                $entityManager->persist($misc);
            }

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Form was successfully saved!'
            );
            return $this->redirectToRoute('app_submissions');
        }

        return $this->render('submission/edit.html.twig', [
            'today' => $today,
            'subMonth' => $subMonth,
            'form' => $form->createView(),
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'form'
        ]);
    }

    /**
     * @Route("/submission/delete", name="delete_submission")
     */
    public function deleteSubmission(Request $request, EntityManagerInterface $entityManager): Response
    {

        $year = $request->get('year');
        $month = $request->get('month');

        if (is_null($year) || is_null($month)) {
            $this->addFlash(
                'danger',
                'Bad Request'
            );
            return $this->redirectToRoute('home');
        }

        $user = $this->getUser();
        $subMonth = DateTIme::createFromFormat('j-m-Y', '01-' . $month . '-' . $year);

        $createdSubmission = $this->getDoctrine()->getRepository(Submission::class)->findOneBy([
            'SubmissionMonth' => $subMonth,
            'UserId' => $user->getId()
        ]);

        if (is_null($createdSubmission)) {
            $this->addFlash(
                'error',
                'There is no submission for ' . $subMonth->format('F')
            );
            return $this->redirectToRoute('home');
        }

        $entityManager->remove($createdSubmission);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Success! Deleted submission for ' . $subMonth->format('F') . '.'
        );
        return $this->redirectToRoute('app_submissions');
    }
}
