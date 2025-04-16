<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints as Assert;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant_total', NumberType::class, [
    'required' => true,
    'empty_data' => '0',
    'constraints' => [
        new NotBlank(['message' => 'Le montant total est requis.']),
        new Regex([
            'pattern' => '/^\d+(\.\d{1,2})?$/',
            'message' => 'Le montant total doit être un nombre décimal positif (ex: 10.99)',
        ]),
        new Assert\Type([
            'type' => 'float',
            'message' => 'Le montant total doit être un nombre valide.',
        ]),
        new Positive(['message' => 'cette valeur doit être supérieur à zéro (pas seulement des espaces).']),
    ],
])
            ->add('date_commande', null, [
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Pending' => 'pending',
                    'Shipped' => 'shipped',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
