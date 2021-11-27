<?php

namespace App\Form\Submission;

use App\Entity\Submission\Submission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
            // ->add('UserId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Submission::class,
        ]);
    }
}
