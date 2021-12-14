<?php

namespace App\Controller;

use App\Entity\Choices\CategoryChoice;
use App\Form\Choices\CategoryChoiceType;
use App\Repository\CategoryChoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_MANAGER")
 * @Route("/operations")
 */
class OperationsController extends AbstractController
{
    /**
     * @Route("/", name="operations_index", methods={"GET"})
     */
    public function index(CategoryChoiceRepository $categoryChoiceRepository): Response
    {
        return $this->render('operations/index.html.twig', [
            'category_choices' => $categoryChoiceRepository->findBy([], ["Category" => "asc"]),
        ]);
    }

    /**
     * @Route("/new", name="operations_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryChoice = new CategoryChoice();
        $form = $this->createForm(CategoryChoiceType::class, $categoryChoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categoryChoice);
            $entityManager->flush();

            return $this->redirectToRoute('operations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operations/new.html.twig', [
            'category_choice' => $categoryChoice,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="operations_show", methods={"GET"})
     */
    public function show(CategoryChoice $categoryChoice): Response
    {
        return $this->render('operations/show.html.twig', [
            'category_choice' => $categoryChoice,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="operations_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CategoryChoice $categoryChoice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryChoiceType::class, $categoryChoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('operations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operations/edit.html.twig', [
            'category_choice' => $categoryChoice,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="operations_delete", methods={"POST"})
     */
    public function delete(Request $request, CategoryChoice $categoryChoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryChoice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categoryChoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('operations_index', [], Response::HTTP_SEE_OTHER);
    }
}
