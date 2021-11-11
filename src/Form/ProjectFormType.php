<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectChoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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
            ->add('TargetHours')
            // ->add('ActualHours')
            ->add('Priority')
            // ->add('WorkResults')
            ->add('Status')
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
