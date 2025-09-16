<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\CustomProductCategory;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre del Producto',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'El nombre es obligatorio']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'El nombre no puede exceder los 255 caracteres'
                    ])
                ]
            ])
            ->add('sku', TextType::class, [
                'label' => 'SKU',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'El SKU no puede exceder los 255 caracteres'
                    ])
                ]
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Peso (kg)',
                'required' => false,
                'scale' => 3,
                'constraints' => [
                    new Assert\PositiveOrZero(['message' => 'El peso debe ser un número positivo o cero'])
                ]
            ])
            ->add('dimensions', TextType::class, [
                'label' => 'Dimensiones (L x W x H)',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 100,
                        'maxMessage' => 'Las dimensiones no pueden exceder los 100 caracteres'
                    ])
                ]
            ])
            ->add('manufacturer', TextType::class, [
                'label' => 'Fabricante',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'El fabricante no puede exceder los 255 caracteres'
                    ])
                ]
            ])
            ->add('warrantyPeriod', IntegerType::class, [
                'label' => 'Período de Garantía (meses)',
                'required' => false,
                'constraints' => [
                    new Assert\PositiveOrZero(['message' => 'El período de garantía debe ser un número positivo o cero'])
                ]
            ])
            ->add('isFeatured', CheckboxType::class, [
                'label' => 'Producto Destacado',
                'required' => false
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
            ->add('customCategories', EntityType::class, [
                'class' => CustomProductCategory::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Categorías Personalizadas',
                'required' => false,
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('c')
                        ->where('c.isActive = :active')
                        ->setParameter('active', true)
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
