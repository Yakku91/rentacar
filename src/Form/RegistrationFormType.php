<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
                'label' => 'Form.firstname',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Form.lastname',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Form.phonenumber',
            ])
            ->add('preferredMethodOfPayment', ChoiceType::class, [
                'label' => 'Form.payment.method',
                'choices' => [
                    'Form.cash' => 'Cash',
                    'Form.girocard' => 'Girocard',
                ]
            ])
            ->add('email', EmailType::class, ['label' => 'Form.email'])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => ['label' => 'Form.password', 'label_attr' => ['class' => 'col-sm-6'],],
                'second_options' => ['label' => 'Form.repeat.password', 'label_attr' => ['class' => 'col-sm-6']],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Form.conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Form.btn.register'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
