<?php

namespace App\Form;

use App\Entity\Avi;
use App\Entity\ReponsesAvi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponsesAviType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reponse')
            ->add('date_reponse', null, [
                'widget' => 'single_text',
            ])
            ->add('avi', EntityType::class, [
                'class' => Avi::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponsesAvi::class,
        ]);
    }
}
