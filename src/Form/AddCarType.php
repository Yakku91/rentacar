<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

class AddCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Form.addCar.name',
            ])
            ->add('category', TextType::class, [
                'label' => 'Form.addCar.category',
            ])
            ->add('seats', NumberType::class, [
                'label' => 'Form.addCar.seats'
            ])
            ->add('luggage', NumberType::class, [
                'label' => 'Form.addCar.luggage'
            ])
            ->add('doors', NumberType::class, [
                'label' => 'Form.addCar.doors',
            ])
            ->add('gear', ChoiceType::class, [
                'label' => 'Form.addCar.gear',
                'choices' => [
                    'Automatik' => 'Automatik',
                    'Manually' => 'Manuell',
                ]
            ])
            ->add('includedKilometres', NumberType::class, [
                'label' => 'Form.addCar.includedKilometers',
            ])
            ->add('pricePerDay', NumberType::class, [
                'label' => 'Form.addCar.pricePerDay',
            ])
            ->add('pricePerWeekend', NumberType::class, [
                'label' => 'Form.addCar.pricePerWeekend',
            ])
            ->add('pricePerWeek', NumberType::class, [
                'label' => 'Form.addCar.pricePerWeek',
            ])
            ->add('pricePerKilometre', NumberType::class, [
                'label' => 'Form.addCar.pricePerExtraKm',
            ])
            ->add('image_file', FileType::class, [
                'label' => 'Form.addCar.image_file',
                'mapped' => false,
                'required' => false,
            ])
            ->add('childSeat', NumberType::class, [
                'label' => 'Child.seat',
                'attr' => [
                    'input' => 'number',
                ],
            ])
            ->add('isDogeCageCompatible', ChoiceType::class, [
                'label' => 'Is.dog.cage.compatible',
                'choices' => [
                    'Yes' => 'Yes',
                    'No' => 'No'
                ]
            ])
            ->add('add', SubmitType::class, [
                'label' => 'Save'
            ]);
    }
}
