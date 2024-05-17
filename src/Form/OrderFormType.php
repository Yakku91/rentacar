<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $now = new \DateTime('now', new \DateTimeZone('Europe/Berlin'));
        $isDogeCageCompatible = $options['isDogeCageCompatible'];
        $numberOfChildSeats = $options['numberOfChildSeats'];
        $builder
            ->add('formOfAddress', ChoiceType::class, [
                'label' => 'Form.select',
                'choices' => [
                    'Form.mr.' => 'male',
                    'Form.mrs.' => 'female',
                    'Form.diverse' => 'diverse'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Form.firstname',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Form.lastname',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Form.phonenumber',
            ])
            ->add('eMail', EmailType::class, [
                'label' => 'Form.email',
            ])
            ->add('methodOfPayment', ChoiceType::class, [
                'label' => 'Form.payment.method',
                'choices' => [
                    'Form.cash' => 'Cash',
                    'Form.girocard' => 'Girocard',
                ]
            ])
            ->add('childSeat', ChoiceType::class, [
                'label' => 'Child.seat',
                'empty_data' => 0,
                'preferred_choices' => [$numberOfChildSeats[array_key_exists(1, $numberOfChildSeats) ? 1 : 0]],
                'choices' => $numberOfChildSeats,
                'disabled' => !array_key_exists(1, $numberOfChildSeats),
            ])
            ->add('dogCage', CheckboxType::class, [
                'label' => 'Form.dog.cage',
                'required' => false,
                'disabled' => $isDogeCageCompatible === false,
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Form.delivery.datetime',
                'widget' => 'single_text',
                'data' => $now,
                'attr' => [
                    'min' => $now->format('Y-m-d H:i:s')
                ]
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Form.return.datetime',
                'widget' => 'single_text',
                'data' => $now,
                'attr' => [
                    'min' => $now->format('Y-m-d H:i:s'),
                    'class' => 'form-control input-inline datetimepicker'
                ]
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Rent.now'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'isDogeCageCompatible' => null,
            'numberOfChildSeats' => null
        ]);
    }
}
