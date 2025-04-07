<?php

namespace App\Form;

use App\Entity\Billetavion;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque')
            ->add('modele')
            ->add('annee')
            ->add('prixParJour')
            ->add('disponible')
            ->add('dateDeLocation', null, [
                'widget' => 'single_text',
            ])
            ->add('dateDeRemise', null, [
                'widget' => 'single_text',
            ])
            ->add('billetavions', EntityType::class, [
                'class' => Billetavion::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
