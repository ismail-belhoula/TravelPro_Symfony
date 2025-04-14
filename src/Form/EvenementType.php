<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_event', null, [
                'label' => "Nom de l'événement",
                'attr' => ['placeholder' => "Entrez le nom de l'événement"]
            ])
            ->add('lieu', null, [
                'label' => "Lieu",
                'attr' => ['placeholder' => "Ex : Paris, Tunis..."]
            ])
            ->add('date_debut', DateType::class, [
                'label' => "Date de début",
                'widget' => 'single_text'
            ])
            ->add('date_fin', DateType::class, [
                'label' => "Date de fin",
                'widget' => 'single_text'
            ])
            ->add('type', null, [
                'label' => "Type d'événement",
                'attr' => ['placeholder' => "Ex : Conférence, Séminaire..."]
            ])
            ->add('imageFile', VichImageType::class, [

                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '50M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG, WEBP).',
                    ]),
                    new NotNull(['message' => 'Please upload an image.'])],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
