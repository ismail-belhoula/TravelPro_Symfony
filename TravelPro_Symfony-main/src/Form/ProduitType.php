<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Validator\Constraints as Assert;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom_produit', TextType::class, [
            'empty_data' => '',
            'constraints' => [
                new NotBlank(['message' => 'Le nom du produit est requis.']),
                new Regex([
                    'pattern' => '/^(?!\s*$)[a-zA-Z0-9\s]+$/',
                    'message' => 'Le nom ne peut contenir que des lettres, des chiffres et des espaces (pas seulement des espaces).',
                ]),
            ],
        ])
        ->add('prix_achat', NumberType::class, [
            'empty_data' => '0',
            'constraints' => [
                new NotBlank(['message' => 'Le prix est requis.']),
                new Regex([
                    'pattern' => '/^\d+(\.\d{1,2})?$/',
                    'message' => 'Le prix doit être un nombre décimal positif (ex: 10.99)',
                ]),
                new Assert\Type([
                    'type' => 'float',
                    'message' => 'Le prix doit être un nombre valide.',
                ]),
                new Positive(['message' => 'cette valeur doit être supérieur à zéro (pas seulement des espaces).']),
            ],
        ])
        
        ->add('quantite_produit', IntegerType::class, [
            'empty_data' => '0',
            'constraints' => [
                new NotBlank(['message' => 'La quantité est requise.']),
                new Regex([
                    'pattern' => '/^\d+$/',
                    'message' => 'La quantité doit être un entier positif.',
                ]),
                new Positive(['message' => 'La quantité doit être un entier supérieur à zéro (pas seulement des espaces).']),
            ],
        ]) 
        ->add('imageFile', FileType::class, [
            'label' => 'Image du produit',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                    'mimeTypesMessage' => 'Veuillez sélectionner une image valide (jpg, png, gif)',
                ])
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
