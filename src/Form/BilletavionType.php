<?php

namespace App\Form;

use App\Entity\Billetavion;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletavionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compagnie')
            ->add('class_Billet')
            ->add('villeDepart')
            ->add('villeArrivee')
            ->add('dateDepart', null, [
                'widget' => 'single_text',
            ])
            ->add('dateArrivee', null, [
                'widget' => 'single_text',
            ])
            ->add('prix')
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
            'data_class' => Billetavion::class,
        ]);
    }
}
