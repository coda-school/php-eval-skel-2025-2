<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rechercher', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        message: 'Vous devez entrer une recherche.'
                    ),
                    new Length(
                        min: 1,
                        max: 50,
                        minMessage : "Votre recherche doit contenir au minimum 1 caractère.",
                        maxMessage: "Votre recherche ne peut pas dépasser 50 caractères.",
                    )
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
