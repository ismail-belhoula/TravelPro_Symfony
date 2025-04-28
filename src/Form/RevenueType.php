<?php

namespace App\Form;

use App\Entity\Revenue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RevenueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_revenue')
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
