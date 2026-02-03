<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire de recherche textuelle.
 * * Ce type de formulaire est généralement utilisé pour filtrer des données
 * ou effectuer des recherches globales sur le site.
 * * @package App\Form
 */
class SearchType extends AbstractType
{
    /**
     * Construit le champ de recherche avec ses contraintes de validation.
     * * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options de configuration du formulaire.
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rechercher', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        message: 'Vous devez entrer une recherche.'
                    ),
                    new Length(
                        max: 50,
                        maxMessage: "Votre recherche ne peut pas dépasser 50 caractères.",
                    )
                ],
                'required' => false,
            ])
        ;
    }

    /**
     * Configure les paramètres par défaut du formulaire de recherche.
     * * Ici, le 'data_class' est défini sur null car les données de recherche
     * ne sont généralement pas liées à une entité spécifique mais traitées
     * comme un tableau de données.
     * * @param OptionsResolver $resolver Le gestionnaire d'options.
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
