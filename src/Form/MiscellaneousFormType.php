<?php

namespace App\Form;

use App\Entity\Miscellaneous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MiscellaneousFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Task')
            ->add('Description')
            ->add('TargetHours')
            ->add('Comment')
            // ->add('SubmissionId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Miscellaneous::class,
        ]);
    }
}