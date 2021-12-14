<?php

namespace App\Controller\Profile\Submission;

use App\Entity\Project;
use App\Entity\Submission\Sections\ProjectEntry;
use App\Entity\Submission\Submission;
use App\Form\Submission\SubmissionFormType;
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
class SubmissionLifeCycleController extends AbstractController
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
            'User' => $user->getId()
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
        $submission->setUser($user);
        $submission->setFormType('WorkingHours');

        $submission->setCreated($today);
        $submission->setUpdated($today);
        $submission->setSubmissionMonth($subMonth);

        $projectChoices = $entityManager->getRepository(Project::class)->findBy([
            'Archive' => false,
        ], ['Name' => 'asc']);

        $form = $this->createForm(SubmissionFormType::class, $submission, [
            'project_choices' => $projectChoices
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($submission);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Form was successfully saved for ' . $subMonth->format('F Y') . '.'
            );
            return $this->redirectToRoute('app_submissions');
        }

        return $this->render('submission/new.html.twig', [
            'today' => $today,
            'subMonth' => $subMonth,
            'form' => $form->createView(),
            'workload' => $user->getWorkload(),
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
            return $this->redirectToRoute('app_submissions');
        }

        $subMonth = DateTIme::createFromFormat('j-m-Y', '01-' . $month . '-' . $year);

        $submission = $this->getDoctrine()->getRepository(Submission::class)->findOneBy([
            'SubmissionMonth' => $subMonth,
            'User' => $user->getId()
        ]);

        if (is_null($submission)) {
            $this->addFlash(
                'error',
                'Form not available for ' . $subMonth->format('F')
            );
            return $this->redirectToRoute('app_submissions');
        }

        $today = new DateTime('now');

        $projectEntriesProjectIds = $submission->getProjectEntries()->map(function (ProjectEntry $projectEntry) {
            return $projectEntry->getProject()->getId();
        });

        $qb = $entityManager->getRepository(Project::class)->createQueryBuilder('p');
        $projectChoices = $qb->select('p')
            ->where('p.Archive = false or p.id IN (:ids)')
            ->setParameter(':ids', $projectEntriesProjectIds)
            ->getQuery()->getResult();

        $form = $this->createForm(SubmissionFormType::class, $submission, [
            'project_choices' => $projectChoices
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submission->setUpdated($today);
            $entityManager->persist($submission);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Form was successfully saved for ' . $subMonth->format('F Y') . '.'
            );
            return $this->redirectToRoute('app_submissions');
        }

        return $this->render('submission/edit.html.twig', [
            'today' => $today,
            'subMonth' => $subMonth,
            'form' => $form->createView(),
            'workload' => $user->getWorkload(),
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
            return $this->redirectToRoute('app_submissions');
        }

        $user = $this->getUser();
        $subMonth = DateTIme::createFromFormat('j-m-Y', '01-' . $month . '-' . $year);

        $createdSubmission = $this->getDoctrine()->getRepository(Submission::class)->findOneBy([
            'SubmissionMonth' => $subMonth,
            'User' => $user->getId()
        ]);

        if (is_null($createdSubmission)) {
            $this->addFlash(
                'error',
                'There is no submission for ' . $subMonth->format('F')
            );
            return $this->redirectToRoute('app_submissions');
        }

        $entityManager->remove($createdSubmission);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Success! Deleted submission for ' . $subMonth->format('F Y') . '.'
        );
        return $this->redirectToRoute('app_submissions');
    }
}
