<?php

namespace App\Form;

use App\DTO\TweetDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TweetType extends AbstractType
{
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
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TweetDTO::class,
        ]);
    }
}
