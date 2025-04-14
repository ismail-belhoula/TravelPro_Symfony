<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'hôtel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom de l\'hôtel',
                    'pattern' => '[a-zA-ZÀ-ÿ\s\-\']+',
                    'title' => 'Lettres, espaces, tirets et apostrophes uniquement'
                ],
                'help' => 'Lettres, espaces, tirets et apostrophes uniquement (max 255 caractères)'
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez la ville',
                    'pattern' => '[a-zA-ZÀ-ÿ\s\-\']+',
                    'title' => 'Lettres, espaces, tirets et apostrophes uniquement'
                ],
                'help' => 'Lettres, espaces, tirets et apostrophes uniquement (max 255 caractères)'
            ])
            ->add('prixParNuit', NumberType::class, [
                'label' => 'Prix par nuit (€)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0.00',
                    'min' => '0',
                    'step' => '0.01'
                ],
                'html5' => true,
                'help' => 'Doit être un nombre positif'
            ])
            ->add('disponible', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label']
            ])
            ->add('nombreEtoile', IntegerType::class, [
                'label' => 'Nombre d\'étoiles',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 7
                ],
                'help' => 'Doit être compris entre 1 et 7'
            ])
            ->add('typeDeChambre', ChoiceType::class, [
                'label' => 'Type de chambre',
                'choices' => [
                    'Single' => 'single',
                    'Double' => 'double',
                    'Triple' => 'triple'
                ],
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez un type',
                'help' => 'Choisissez parmi single, double ou triple'
            ])
            ->add('dateCheckIn', DateType::class, [
                'label' => 'Date de check-in',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'help' => 'Doit être aujourd\'hui ou ultérieure'
            ])
            ->add('dateCheckOut', DateType::class, [
                'label' => 'Date de check-out',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'help' => 'Doit être après la date de check-in'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}