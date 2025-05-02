<?php

namespace App\Form;

use App\Entity\Billetavion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BilletavionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compagnie', TextType::class, [
                'label' => 'Compagnie aérienne',
                'attr' => [
                    'pattern' => "[a-zA-ZÀ-ÿ\s\-']+",
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la compagnie'
                ],
                'help' => 'Lettres, espaces, tirets et apostrophes uniquement'
            ])
            ->add('class_Billet', ChoiceType::class, [
                'label' => 'Classe',
                'choices' => [
                    'Économique' => 'economique',
                    'Affaire' => 'affaire',
                    'Première' => 'premiere'
                ],
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez une classe',
                'help' => 'Choisissez parmi Économique, Affaire ou Première'
            ])
            ->add('villeDepart', TextType::class, [
                'label' => 'Ville de départ',
                'attr' => [
                    'pattern' => "[a-zA-ZÀ-ÿ\s\-']+",
                    'class' => 'form-control',
                    'placeholder' => 'Ville de départ'
                ],
                'help' => 'Lettres, espaces, tirets et apostrophes uniquement'
            ])
            ->add('villeArrivee', TextType::class, [
                'label' => 'Ville d\'arrivée',
                'attr' => [
                    'pattern' => "[a-zA-ZÀ-ÿ\s\-']+",
                    'class' => 'form-control',
                    'placeholder' => 'Ville d\'arrivée'
                ],
                'help' => 'Lettres, espaces, tirets et apostrophes uniquement'
            ])
            ->add('dateDepart', DateType::class, [
                'label' => 'Date de départ',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'help' => 'Doit être aujourd\'hui ou ultérieure'
            ])
            ->add('dateArrivee', DateType::class, [
                'label' => 'Date d\'arrivée',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'help' => 'Doit être après la date de départ'
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix (€)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0.00',
                    'min' => '0',
                    'step' => '0.01'
                ],
                'html5' => true,
                'help' => 'Doit être un nombre positif'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Billetavion::class,
        ]);
    }
}