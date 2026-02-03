<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire de modification du profil utilisateur.
 * * Permet à l'utilisateur de mettre à jour ses informations personnelles
 * telles que son nom d'affichage et sa biographie.
 * * @package App\Form
 */
class UserType extends AbstractType
{
    /**
     * Construit le formulaire de profil.
     * * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options de configuration du formulaire.
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        message: "Votre nouveau nom d'utilisateur ne peut pas être vide"
                    ),
                    new Length(
                        min: 3,
                        max:30,
                        minMessage: "Votre nouveau pseudo doit contenir minimum 3 caracteres",
                        maxMessage: "Votre nouveau pseudo peut contenir au maximum 30 caracteres"
                    )
                ],
                'required' => true,
            ])
            ->add('bio', TextType::class)
        ;
    }

    /**
     * Configure les options de base pour ce type de formulaire.
     * * @param OptionsResolver $resolver Le résolveur d'options.
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
