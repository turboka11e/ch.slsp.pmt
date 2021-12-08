<?php

namespace App\Form\Submission;

use App\Entity\Submission\Submission;
use App\Form\Submission\Sections\MiscellaneousEntryFormType;
use App\Form\Submission\Sections\OperationEntryFormType;
use App\Form\Submission\Sections\ProjectEntryFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmissionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('FormType')
            ->add('Workdays', NumberType::class, [
                'attr' => [
                    'style' => 'width:5em',
                    'min' => "0",
                    'placeholder' => "0",
                    'step' => "0.5",
                    'role' => 'status',
                ],
                'html5' => true
            ])
            ->add('PlannedAbsences', NumberType::class, [
                'attr' => [
                    'style' => 'width:5em',
                    'min' => "0",
                    'step' => "0.5",
                ],
                'html5' => true
            ])
            ->add('FurtherAbsences', NumberType::class, [
                'attr' => [
                    'style' => 'width:5em',
                    'min' => "0",
                    'step' => "0.5",
                ],
                'html5' => true
            ])
            ->add('operationEntries', CollectionType::class, [
                'label' => false,
                'entry_type' => OperationEntryFormType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('projectEntries', CollectionType::class, [
                'label' => false,
                'entry_type' => ProjectEntryFormType::class,
                'entry_options' => [
                    'label' => false,
                    'project_choices' => $options['project_choices'],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('miscellaneousEntries', CollectionType::class, [
                'label' => false,
                'entry_type' => MiscellaneousEntryFormType::class,
                'entry_options' => ['label' => false,],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('Submit', SubmitType::class)
            // ->add('UserId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Submission::class,
            ])->setRequired([
                'project_choices'
            ]);
    }
}
