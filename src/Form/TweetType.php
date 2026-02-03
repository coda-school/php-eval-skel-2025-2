<?php

namespace App\Form;

use App\DTO\TweetDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Formulaire de création ou d'édition d'un Tweet.
 * * Ce type gère le texte du message, l'upload d'une image ainsi que
 * l'option de suppression de l'image existante via un DTO.
 * * @package App\Form
 */
class TweetType extends AbstractType
{
    /**
     * Construit les champs du formulaire Tweet.
     * * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options de configuration.
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        message: 'Vous devez entrer un message'
                    ),
                    new Length(
                        min: 1,
                        max: 280,
                        minMessage: "Vous devez entrer un message d'au moins 1 caractère",
                        maxMessage: "Votre message ne peut pas excéder 280 caractères"
                    )
                ],
                "required" => true,
                "help" => "Votre message doit être compris entre 1 et 280 caractères",
            ])
            ->add('image', FileType::class, [
                'attr' => ['title' => 'Choisir une image',
                    'placeholder' => ''],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File(
                        maxSize: '1024k',
                        extensions: ['png', 'jpg', 'jpeg', 'gif'],
                        extensionsMessage: "Merci d'ajouter une image au format png, jpg, jpeg ou gif",
                    )
                ]
            ])
            ->add('removeImage', CheckboxType::class, [
                'label' => "Supprimer l'image",
                'required' => false,
            ])
        ;
    }

    /**
     * Configure les options du formulaire.
     * * Lie ce formulaire à la classe TweetDTO pour faciliter le transport
     * et la validation des données collectées.
     * * @param OptionsResolver $resolver Le résolveur d'options.
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TweetDTO::class,
        ]);
    }
}
