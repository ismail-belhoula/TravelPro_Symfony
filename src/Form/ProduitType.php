<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Panier;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_produit')
            ->add('prix_achat')
            ->add('quantite_produit')
            ->add('prix_vente')
            ->add('image_path')
            ->add('commandes', EntityType::class, [
                'class' => Commande::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('paniers', EntityType::class, [
                'class' => Panier::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
