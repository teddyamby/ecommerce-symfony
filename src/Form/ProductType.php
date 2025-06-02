<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a product name']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a description']),
                ],
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'USD',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a price']),
                    new Positive(['message' => 'Price must be positive']),
                ],
            ])
            ->add('quantity', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a quantity']),
                    new PositiveOrZero(['message' => 'Quantity must be zero or positive']),
                ],
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG or WebP)',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}