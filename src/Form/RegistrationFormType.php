<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Classe de formulaire pour l'inscription des utilisateurs.
 * * Ce formulaire gère la création de compte en incluant les validations de sécurité
 * pour le nom d'utilisateur, l'email et le mot de passe.
 * * @package App\Form
 */

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * Construit le formulaire d'inscription.
         * * @param FormBuilderInterface $builder Le constructeur de formulaire.
         * @param array $options Un tableau d'options de configuration.
         * @return void
         */
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        message: "Votre nom d'utilisateur ne peut pas être vide"
                    ),
                    new Length(
                        min: 3,
                        max:30,
                        minMessage: "Votre pseudo doit contenir minimum 3 caracteres",
                        maxMessage: "Votre pseudo peut contenir au maximum 30 caracteres"
                    )
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(
                        message: "Votre email ne peut pas être vide"
                    )
                ],
                'required' => true,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos termes ...',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci d'entrer un mot de passe",
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'required' => true,
            ])
        ;
    }

    /**
     * Configure les options par défaut pour ce formulaire.
     * * Définit notamment la classe de données liée (User::class).
     * * @param OptionsResolver $resolver Le résolveur d'options.
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
