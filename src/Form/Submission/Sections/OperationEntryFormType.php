<?php

namespace App\Form\Submission\Sections;

use App\Entity\Choices\CategoryChoice;
use App\Entity\Submission\Sections\OperationEntry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationEntryFormType extends AbstractType
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
            ->add('Status', ChoiceType::class, [
                'choices' => ['Cont.' => 'Continuing', 'Hold' => 'Hold', 'Done' => 'Done'],
                'attr' => [
                    'style' => 'width:6em',
                ],
                ])
            ->add('WorkResults', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'style'  => 'height: 1em'
                ]
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
            'data_class' => OperationEntry::class,
        ]);
    }
}
