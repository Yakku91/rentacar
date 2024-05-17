<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('admin', CheckboxType::class, [
            'label' => 'Administrator',
            'required' => false,

        ])
            ->add('user', CheckboxType::class, [
                'label' => 'User',
                'required' => false,

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filter'
            ]);
    }
}
