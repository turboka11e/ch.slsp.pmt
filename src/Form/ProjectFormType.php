<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectChoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ])
            ->add('Description')
            ->add('TargetHours', IntegerType::class)
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
            function ($category) {
                return $category;
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
