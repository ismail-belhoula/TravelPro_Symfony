<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Modifiez les contraintes pour 'marque' et 'modele' comme suit :

->add('marque', TextType::class, [
    'attr' => [
        'class' => 'form-control',
        'placeholder' => 'Marque de la voiture'
    ],
    'label' => 'Marque',
    'constraints' => [
        new Assert\NotBlank(['message' => "La marque est obligatoire"]),
        new Assert\Regex([
            'pattern' => "/^[a-zA-Z0-9]+$/",
            'message' => "La marque ne doit pas contenir d'espaces ni de caractères spéciaux"
        ])
    ]
])
->add('modele', TextType::class, [
    'attr' => [
        'class' => 'form-control',
        'placeholder' => 'Modèle de la voiture'
    ],
    'label' => 'Modèle',
    'constraints' => [
        new Assert\NotBlank(['message' => "Le modèle est obligatoire"]),
        new Assert\Regex([
            'pattern' => "/^[a-zA-Z0-9]+$/",
            'message' => "Le modèle ne doit pas contenir d'espaces ni de caractères spéciaux"
        ])
    ]
])
            ->add('annee', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Année de fabrication'
                ],
                'label' => 'Année',
                'constraints' => [
                    new Assert\NotBlank(['message' => "L'année est obligatoire"]),
                    new Assert\Range([
                        'min' => 2000,
                        'max' => 2025,
                        'notInRangeMessage' => "L'année doit être comprise entre {{ min }} et {{ max }}"
                    ])
                ]
            ])
            ->add('prixParJour', NumberType::class, [
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'class' => 'form-control',
                    'placeholder' => 'Prix par jour de location'
                ],
                'label' => 'Prix par jour',
                'constraints' => [
                    new Assert\NotBlank(['message' => "Le prix par jour est obligatoire"]),
                    new Assert\Type([
                        'type' => 'float',
                        'message' => "Le prix par jour doit être un nombre décimal."
                    ]),
                    new Assert\Positive([
                        'message' => "Le prix par jour doit être positif."
                    ])
                ]
            ])
            ->add('disponible', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('dateDeLocation', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de location',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThanOrEqual([
                        'value' => "today",
                        'message' => "La date de location doit être supérieure ou égale à aujourd'hui"
                    ])
                ]
            ])
            ->add('dateDeRemise', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de remise',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Expression([
                        'expression' => "this.getParent().get('dateDeLocation').getData() === null or value === null or value > this.getParent().get('dateDeLocation').getData()",
                        'message' => "La date de remise doit être supérieure à la date de location"
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}