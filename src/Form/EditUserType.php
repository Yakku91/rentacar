<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Transformers\ArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    private $arrayTransformer;

    public function __construct(ArrayTransformer $arrayTransformer)
    {
        $this->arrayTransformer = $arrayTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::Class, [
                'label' => 'Email'
            ])
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
            ->add('roles', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ]
            ])
            ->add('phoneNumber', TextType::class, ['label' => 'Phonenumber'])
            ->add('save', SubmitType::class, ['label' => 'Save']);

        $builder->get('roles')->addModelTransformer($this->arrayTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
