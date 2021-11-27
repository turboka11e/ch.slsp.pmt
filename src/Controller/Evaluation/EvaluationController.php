<?php

namespace App\Controller\Evaluation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvaluationController extends AbstractController
{
    /**
     * @Route("/evaluation", name="submission_evaluation")
     */
    public function index(): Response
    {

        // $opIds = $operations->map(function (Operation $o) {
        //     echo "test " . $o->getId();
        //     return $o->getId();
        // });
        // $opIds = $opIds->toArray();
        // echo strval($opIds[0]);
        // $qb->delete(Operation::class, 'o')
        //     ->andWhere($qb->expr()->notIn('o.id', $opIds))
        //     ->andWhere($qb->expr()->eq('o.submission', $task->getSubmission()->getId()));

        // echo sizeof($opIds);
        // $qb->getQuery()->execute();

        return $this->render('results/index.html.twig', [
            'controller_name' => 'ResultsController',
        ]);
    }
}
