<?php

namespace App\Form;

use App\Entity\ReponsesAvi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints as Assert;

class ReponsesAviFrontType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('reponse', TextareaType::class, [
        'label' => 'Votre réponse',
        'attr' => [
          'class' => 'form-control',
          'rows' => 5,
          'placeholder' => 'Écrivez votre réponse ici (10 caractères minimum)'
        ],
        'constraints' => [
          new Assert\NotBlank([
            'message' => 'La réponse ne peut pas être vide'
          ]),
          new Assert\Length([
            'min' => 10,
            'max' => 1000,
            'minMessage' => 'La réponse doit faire au moins {{ limit }} caractères',
            'maxMessage' => 'La réponse ne peut pas dépasser {{ limit }} caractères'
          ])
        ]
      ])
      ->add('avi', HiddenType::class, [
        'data' => $options['avi_id'],
        'mapped' => false
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => ReponsesAvi::class,
      'avi_id' => null,
      'attr' => [
        'id' => 'reponse-avis-form',
        'class' => 'needs-validation',
        'novalidate' => 'novalidate'
      ]
    ]);
  }
}
