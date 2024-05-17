<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterOrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pending', CheckboxType::class, [
            'label' => 'Pending',
            'required' => false,

        ])
            ->add('allowed', CheckboxType::class, [
                'label' => 'Allowed',
                'required' => false,

            ])
            ->add('denied', CheckboxType::class, [
                'label' => 'Denied',
                'required' => false,

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filter'
            ]);
    }
}
