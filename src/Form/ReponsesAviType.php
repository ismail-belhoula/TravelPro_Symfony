<?php

namespace App\Form;

use App\Entity\Avi;
use App\Entity\ReponsesAvi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReponsesAviType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reponse', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Entrez votre réponse ici...',
                    'novalidate' => 'novalidate'
                ],
                'label' => 'Réponse',
                'required' => true
            ])
            ->add('date_reponse', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'novalidate' => 'novalidate'
                ],
                'label' => 'Date de réponse',
                'required' => true
            ])
            ->add('avi', EntityType::class, [
                'class' => Avi::class,
                'choice_label' => 'commentaire',
                'attr' => [
                    'class' => 'form-control',
                    'novalidate' => 'novalidate'
                ],
                'label' => 'Avis associé',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponsesAvi::class,
            'attr' => [
                'class' => 'reponses-avi-form needs-validation',
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}