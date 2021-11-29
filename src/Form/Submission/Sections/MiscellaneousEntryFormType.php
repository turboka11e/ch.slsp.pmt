<?php

namespace App\Form\Submission\Sections;

use App\Entity\Submission\Sections\MiscellaneousEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MiscellaneousEntryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Task')
            ->add('Description')
            ->add('TargetHours', NumberType::class, [
                'attr' => [
                    'step' => "0.5",
                    'min' => "0",
                    'style' => 'width:5em',
                ],
                'html5' => true
            ])
            ->add('Comment')
            // ->add('SubmissionId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MiscellaneousEntry::class,
        ]);
    }
}
