<?php

namespace App\Form;

use App\Entity\Deponse;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'nomProduit', // Ensure 'nomProduit' is a valid property of 'Produit'
                'label' => 'Produit',
                'placeholder' => 'Sélectionnez un produit',
            ])
            ->add('prix_achat', MoneyType::class, [
                'currency' => 'TND',
                'label' => 'Prix d\'achat'
            ])
            ->add('quantite_produit', IntegerType::class, [
                'label' => 'Quantité'
            ])
            ->add('Date_achat', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'achat'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Deponse::class,
        ]);
    }
}