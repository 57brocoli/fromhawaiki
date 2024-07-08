<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\PageSection;
use App\Entity\SectionContent;
use App\Entity\Style;
use App\Entity\StyleGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['class' => 'tinymce'],
                'required' => false,
            ])
            ->add('images', FileType::class, [
                'attr'=> [
                    'class'=>'file'
                ],
                'label'=>'Ajouter des images',
                'multiple'=>true,
                'required'=>false,
                'mapped'=>false,
            ])
            ->add('styles', EntityType::class, [
                'label'=>'Styles',
                'class' => Style::class,
                'choice_label' => 'formattedLabel',
                'choice_attr' => function ($choiceValue, $key, $value) {
                    // Vérifie si la propriété de l'entité est 'background-color' ou 'color'
                    if ($choiceValue->getProperty() === 'background-color' || $choiceValue->getProperty() === 'color') {
                        // Récupère la valeur de la propriété $value de l'entité Style
                        $colorValue = $choiceValue->getValue(); // Assurez-vous que cette méthode correspond à votre implémentation

                        // Retourne un tableau d'attributs avec la classe 'style-option' et la couleur de fond correspondante
                        return [
                            'class' => 'style-option',
                            'style' => 'background-color: ' . $colorValue // Applique la couleur de fond dynamique
                        ];
                    }
                    return []; // Retourne un tableau vide pour les autres options
                },
                'multiple' => true,
                'required'=>false,
                'attr' => [
                    'class' => 'entity'
                ]
            ])
            ->add('class', EntityType::class, [
                'label'=>'Groupe de styles',
                'class' => StyleGroup::class,
                'choice_label' => 'name',
                // 'mapped'=>false,
                'multiple' => true,
                'required'=>false,
                'attr' => [
                    'class' => 'entity'
                ]
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'submit'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SectionContent::class,
        ]);
    }
}