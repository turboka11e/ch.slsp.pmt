<?php

namespace App\Form\Submission\Sections;

use App\Entity\Choices\ProjectChoice;
use App\Entity\Project;
use App\Entity\Submission\Sections\ProjectEntry;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectEntryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'Name',
                'attr' => [
                            'style' => 'width: min-content'
                ]
            ])
            ->add('Description')
            ->add('TargetHours', NumberType::class, [
                'attr' => [
                    'step' => "0.5",
                    'min' => "0",
                    'style' => 'width:6em',
                ],
                'html5' => true
            ])
            ->add('ActualHours', NumberType::class, [
                'attr' => [
                    'step' => "0.5",
                    'min' => "0",
                    'style' => 'width: 6em',
                ],
                'required' => false,
                'html5' => true
            ])
            ->add('Priority', ChoiceType::class, [
                'choices' => ['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'],
                'attr' => [
                    'style' => 'width:7em',
                ],
            ])
            ->add('Status', ChoiceType::class, [
                'choices' => ['Cont.' => 'Continuing', 'Hold' => 'Hold', 'Done' => 'Done'],
                'attr' => [
                    'style' => 'width:6em',
                ],
            ])
            ->add('WorkResults', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'style'  => 'height: 1em'
                ]
            ])
            // ->add('SubmissionId')
        ;

        // $builder->get('Name')->addModelTransformer(new CallbackTransformer(
        //     function ($project) {
        //         if (!is_null($project)) {
        //             $cat = new ProjectChoice();
        //             $cat = $cat->setProject($project);
        //             return $cat;
        //         }
        //         return $project;
        //     },
        //     function (ProjectChoice $project) {
        //         return $project->getProject();
        //     }
        // ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectEntry::class,
        ]);
    }
}
