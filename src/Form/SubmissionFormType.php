<?php

namespace App\Form;

use App\Entity\Submission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmissionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('FormType')
            ->add('Workdays', IntegerType::class, [
                'attr' => [
                    'style' => 'width:5em',
                    'min' => "0",
                    'placeholder' => "0"
                ]
            ])
            ->add('PlannedAbsences', IntegerType::class, [
                'attr' => [
                    'style' => 'width:5em',
                    'min' => "0",
                ]
            ])
            ->add('FurtherAbsences', IntegerType::class, [
                'attr' => [
                    'style' => 'width:5em',
                    'min' => "0",
                ]
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
