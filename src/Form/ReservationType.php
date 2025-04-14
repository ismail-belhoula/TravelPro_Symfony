<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Voiture;
use App\Entity\Billetavion;
use App\Entity\Hotel;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('voiture', EntityType::class, [
                'class' => Voiture::class,
                'choice_label' => 'marque',
                'required' => false,
            ])
            ->add('billetAvion', EntityType::class, [
                'class' => Billetavion::class,
                'choice_label' => 'compagnie',
                'required' => false,
            ])
            ->add('hotel', EntityType::class, [
                'class' => Hotel::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'id_client',
            ])
            ->add('statut')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}