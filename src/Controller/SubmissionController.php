<?php

namespace App\Controller;

use App\Entity\CategoryChoice;
use App\Entity\Submission;
use App\Form\SubmissionFormType;
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

        $categoryChoices = $this->getDoctrine()->getRepository(CategoryChoice::class)->findAll();
        $submission = new Submission();

        $submission->setUserId($this->getUser());
      
        $submission->setCreated(new DateTime('now'));
        $submission->setUpdated(new DateTime('now'));
        $today = new DateTime('now');
        $submission->setSubmissionMonth($today->modify('next month'));

        $form = $this->createForm(SubmissionFormType::class, $submission);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($submission);
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
