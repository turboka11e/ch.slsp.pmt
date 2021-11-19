<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @IsGranted("ROLE_USER")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $today = new DateTime('now');
        $nextMonth = $today->modify('first day of next month');

        $defaultData = ['Date' => new DateTime('first day of next month')];
        $form = $this->createFormBuilder($defaultData)
            ->setAction($this->generateUrl('new_submission'))
            ->setMethod('GET')
            ->add('Date', DateType::class, [
                'years' => range(date('Y'), date('Y') + 10)
            ])
            ->add('Create', SubmitType::class)
            ->getForm();

        return $this->render('home/index.html.twig', [
            'today' => new DateTime('now'),
            'nextMonth' => $nextMonth,
            'createForm' => $form->createView(),
            'controller_name' => 'HomeController',
        ]);
    }
}
