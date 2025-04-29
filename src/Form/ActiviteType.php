<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('nom_activite')
        ->add('description')
        ->add('date_debut', DateType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('date_fin', DateType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('evenement', EntityType::class, [
            'class' => Evenement::class,
            'choice_label' => 'nom_event',
            'placeholder' => 'Choisir un événement',
            'required' => true,
            'attr' => ['class' => 'form-select'],
        ]);
}
}
