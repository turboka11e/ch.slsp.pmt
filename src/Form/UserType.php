<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('email')
            ->add('name')
            ->add('surname')
            ->add('workload', PercentType::class, [
                'html5' => true,
                'attr' => [
                    'step' => 10,
                    'max' => 100,
                    'min' => 0,
                ],
            ])
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => ['Manager' => 'ROLE_MANAGER'],
                    'expanded' => true,
                    'multiple' => true,
                    'required' => true,
                ]
            );
        // ->add('password')
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
