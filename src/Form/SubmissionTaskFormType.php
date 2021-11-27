<?php

namespace App\Form;

use App\Entity\SubmissionTask;
use App\Form\Submission\Sections\MiscellaneousFormType;
use App\Form\Submission\Sections\OperationFormType;
use App\Form\Submission\Sections\ProjectFormType;
use App\Form\Submission\SubmissionFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmissionTaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('submission', SubmissionFormType::class)
            ->add('operations', CollectionType::class, [
                'label' => false,
                'entry_type' => OperationFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('projects', CollectionType::class, [
                'label' => false,
                'entry_type' => ProjectFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('miscellaneouses', CollectionType::class, [
                'label' => false,
                'entry_type' => MiscellaneousFormType::class,
                'entry_options' => ['label' => false,],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SubmissionTask::class,
        ]);
    }
}
