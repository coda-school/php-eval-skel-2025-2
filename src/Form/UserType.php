<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
