<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditUserFormType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                'label' => 'Firstname',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Lastname',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Phonenumber',
            ])
            ->add('preferredMethodOfPayment', ChoiceType::class, [
                'label' => 'Form.payment.method',
                'choices' => [
                    'Form.cash' => 'Cash',
                    'Form.girocard' => 'Girocard',
                ]
            ])
            ->add('email', EmailType::Class, [
                'label' => 'Email'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
