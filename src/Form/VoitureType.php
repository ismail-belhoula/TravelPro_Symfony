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

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', TextType::class, [
                'attr' => [
                    'pattern' => '[a-zA-Z0-9\s]+',
                    'class' => 'form-control',
                    'placeholder' => 'Marque de la voiture'
                ],
                'help' => 'Ne doit pas contenir de caractères spéciaux',
                'label' => 'Marque'
            ])
            ->add('modele', TextType::class, [
                'attr' => [
                    'pattern' => '[a-zA-Z0-9\s]+',
                    'class' => 'form-control',
                    'placeholder' => 'Modèle de la voiture'
                ],
                'help' => 'Ne doit pas contenir de caractères spéciaux',
                'label' => 'Modèle'
            ])
            ->add('annee', IntegerType::class, [
                'attr' => [
                    'min' => 2000,
                    'max' => 2025,
                    'class' => 'form-control',
                    'placeholder' => 'Année de fabrication'
                ],
                'help' => 'Doit être entre 2000 et 2025',
                'label' => 'Année'
            ])
            ->add('prixParJour', NumberType::class, [
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'class' => 'form-control',
                    'placeholder' => 'Prix par jour de location'
                ],
                'help' => 'Doit être un nombre décimal positif',
                'label' => 'Prix par jour'
            ])
            ->add('disponible', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('dateDeLocation', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'help' => 'Doit être supérieure ou égale à aujourd\'hui',
                'label' => 'Date de location',
                'required' => false
            ])
            ->add('dateDeRemise', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'help' => 'Doit être supérieure à la date de location',
                'label' => 'Date de remise',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}