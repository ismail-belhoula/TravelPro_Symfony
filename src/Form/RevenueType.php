<?php

namespace App\Form;

use App\Entity\Revenue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RevenueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_revenue', ChoiceType::class, [
                'choices' => [
                    'Billet Avion' => 'billet_avion',
                    'Réservation Hôtel' => 'reservation_hotel',
                    'Réservation Voiture' => 'reservation_voiture',
                    'Vente Produit' => 'vente_produit',
                ],
                'placeholder' => 'Choisir un type de revenu', // optional placeholder
                'required' => true, // Make sure it's required
            ])
            ->add('date_revenue', null, [
                'widget' => 'single_text',
            ])
            ->add('montant_total')
            ->add('commission')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Revenue::class,
        ]);
    }
}
