<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_event', TextType::class, [
                'label' => "Nom de l'événement",
                'attr' => ['placeholder' => "Entrez le nom de l'événement"],
                'constraints'=>[
                    new NotBlank(['message' => 'Please upload an image.'])]
            ])
            ->add('lieu', TextType::class, [
                'label' => "Lieu",
                'attr' => ['placeholder' => "Ex : Paris, Tunis..."],
                'constraints'=>[
                    new NotBlank(['message' => 'Please upload an image.'])]
            ])
            ->add('date_debut', DateTimeType::class, [
                'label' => "Date de début",
                'widget' => 'single_text',
                'constraints'=>[
                    new NotBlank(['message' => 'Please upload an image.'])]
            ])
            ->add('date_fin', DateTimeType::class, [
                'label' => "Date de fin",
                'widget' => 'single_text',
                'constraints'=>[
                    new NotBlank(['message' => 'Please upload an image.'])]
            ])
            ->add('type', TextType::class, [
                'label' => "Type d'événement",
                'attr' => ['placeholder' => "Ex : Conférence, Séminaire..."],
                'constraints'=>[
                    new NotBlank(['message' => 'Please upload an image.'])]
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
            ->add('latitude', HiddenType::class, [
                'required' => false, // Or true if location is mandatory
                // We'll set the value using JavaScript
            ])
            ->add('longitude', HiddenType::class, [
                'required' => false, // Or true if location is mandatory
                // We'll set the value using JavaScript
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
