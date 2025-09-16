<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\CustomProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CustomProductCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre de la Categoría',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'El nombre es obligatorio']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'El nombre no puede exceder los 255 caracteres'
                    ])
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'El slug es obligatorio']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'El slug no puede exceder los 255 caracteres'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-z0-9-]+$/',
                        'message' => 'El slug solo puede contener letras minúsculas, números y guiones'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
                'attr' => ['rows' => 4]
            ])
            ->add('parent', EntityType::class, [
                'class' => CustomProductCategory::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Categoría Padre',
                'placeholder' => 'Seleccionar categoría padre (opcional)',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('c')
                        ->where('c.isActive = :active')
                        ->setParameter('active', true)
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('sortOrder', IntegerType::class, [
                'label' => 'Orden de Clasificación',
                'required' => false,
                'data' => 0,
                'constraints' => [
                    new Assert\Type(['type' => 'integer', 'message' => 'El orden debe ser un número entero'])
                ]
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Activa',
                'required' => false,
                'data' => true
            ])
            ->add('metaTitle', TextType::class, [
                'label' => 'Meta Título',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'El meta título no puede exceder los 255 caracteres'
                    ])
                ]
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'Meta Descripción',
                'required' => false,
                'attr' => ['rows' => 3]
            ])
            ->add('metaKeywords', TextareaType::class, [
                'label' => 'Meta Keywords',
                'required' => false,
                'attr' => ['rows' => 2]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomProductCategory::class,
        ]);
    }
}
