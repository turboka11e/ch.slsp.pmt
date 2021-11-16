<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\CategoryChoice;
use App\Entity\Operation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class OperationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Category', EntityType::class, [
                'class' => CategoryChoice::class,
                'choice_label' => 'Category',
                'choice_value' => 'Category',
                'attr' => [
                    'style' => 'width: min-content'
                ]
            ])
            ->add('Description')
            ->add('Hours', NumberType::class, [
                'attr' => [
                    'step' => "0.5",
                    'min' => "0",
                    'style' => 'width:5em',
                ],
                'html5' => true
            ])
            ->add('Priority', ChoiceType::class, [
                'choices' => ['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'],
                'attr' => [
                    'style' => 'width:7em',
                ],
            ])
            // ->add('WorkResults')
            ->add('Status', ChoiceType::class, [
                'choices' => ['Cont.' => 'Continuing', 'Hold' => 'Hold', 'Done' => 'Done'],
                'attr' => [
                    'style' => 'width:6em',
                ],
            ])
            // ->add('SubmissionId')
        ;

        $builder->get('Category')->addModelTransformer(new CallbackTransformer(
            function ($category) {
                if (!is_null($category)) {
                    $cat = new CategoryChoice();
                    $cat = $cat->setCategory($category);
                    return $cat;
                }
                return $category;
            },
            function (CategoryChoice $category) {
                return $category->getCategory();
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
