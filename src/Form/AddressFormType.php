<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', CountryType::class, [
                'label' => 'Form.country',
                'preferred_choices' => ['DE'],
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Form.zipCode',
            ])
            ->add('city', TextType::class, [
                'label' => 'Form.city',
            ])
            ->add('street', TextType::class, [
                'label' => 'Form.street',
            ])
            ->add('houseNumber', TextType::class, [
                'label' => 'Form.houseNumber',
            ])
            ->add('comments', TextareaType::class, [
                'label' => 'Form.comments',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'csrf_protection' => false,
        ]);
    }
}
