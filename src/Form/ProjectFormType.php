<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectChoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', EntityType::class, [
                'class' => ProjectChoice::class,
                'choice_label' => 'Project',
                'choice_value' => 'Project',
            ])
            ->add('Description')
            ->add('TargetHours', NumberType::class, [
                'attr' => [
                    'step' => "0.5",
                ],
                'html5' => true
            ])
            // ->add('ActualHours')
            ->add('Priority', ChoiceType::class, [
                'choices' => ['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High']
            ])
            // ->add('WorkResults')
            ->add('Status', ChoiceType::class, [
                'choices' => ['Cont.' => 'Continuing', 'Hold' => 'Hold', 'Done' => 'Done']
            ])
            // ->add('SubmissionId')
        ;

        $builder->get('Name')->addModelTransformer(new CallbackTransformer(
            function ($project) {
                if(!is_null($project)) {
                    $cat = new ProjectChoice();
                    $cat = $cat->setProject($project);
                    return $cat;
                }
                return $project;
            },
            function (ProjectChoice $category) {
                return $category->getProject();
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
