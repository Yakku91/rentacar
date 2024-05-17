<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class AnalysisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('startDate', DateType::class, [
            'label' => 'Stats.startTime',
            'widget' => 'single_text',
            'data' => new \DateTime(),
            'attr' => [
                'max' => date('Y-m-d')
            ]
        ])
            ->add('endDate', DateType::class, [
                'label' => 'Stats.endTime',
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'attr' => [
                    'max' => date('Y-m-d')
                ]
            ]);
    }
}
