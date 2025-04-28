<?php

namespace App\Form;

use App\Entity\Hotel;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('ville')
            ->add('prixParNuit')
            ->add('disponible')
            ->add('nombreEtoile')
            ->add('typeDeChambre')
            ->add('dateCheckIn', null, [
                'widget' => 'single_text',
            ])
            ->add('dateCheckOut', null, [
                'widget' => 'single_text',
            ])
            ->add('voitures', EntityType::class, [
                'class' => Voiture::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}
