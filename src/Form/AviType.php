<?php

namespace App\Form;

use App\Entity\Avi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AviType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', HiddenType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'rating-value',
                ],
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Votre commentaire... (10 caractères minimum)'
                ]
            ])
            ->add('date_publication', DateTimeType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control datetimepicker'
                ]
            ])
            ->add('est_accepte', CheckboxType::class, [
                'label' => 'Avis accepté ?',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avi::class,
            'attr' => [
                'id' => 'avis-form',
                'class' => 'needs-validation',
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}