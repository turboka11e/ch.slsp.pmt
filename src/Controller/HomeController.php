<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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

        return $this->render('home/index.html.twig', [
            'today' => new DateTime('now'),
            'nextMonth' => $nextMonth,
            'controller_name' => 'HomeController',
        ]);
    }
}
