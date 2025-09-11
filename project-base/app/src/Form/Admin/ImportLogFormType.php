<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Model\ImportLog\ImportLogData;
use Shopsys\FrameworkBundle\Form\DatePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class ImportLogFormType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startTime', DatePickerType::class, [
                'constraints' => [
                    new Constraints\NotBlank(['message' => 'Please enter start time']),
                ],
            ])
            ->add('endTime', DatePickerType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Pending' => 'pending',
                    'Running' => 'running',
                    'Success' => 'success',
                    'Error' => 'error',
                ],
                'constraints' => [
                    new Constraints\NotBlank(['message' => 'Please select status']),
                ],
            ])
            ->add('errorMessage', TextareaType::class, [
                'required' => false,
            ])
            ->add('recordsChanged', IntegerType::class, [
                'required' => false,
            ])
            ->add('importType', TextType::class, [
                'constraints' => [
                    new Constraints\NotBlank(['message' => 'Please enter import type']),
                ],
            ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImportLogData::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
