<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\FigGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre",
                'attr' => [
                    'placeholder' => "Titre de l'article"
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => "Contenu",
                'attr' => [
                    'placeholder' => "Contenu de l'article"
                ]
            ])
            ->add('figGroup', EntityType::class, [
                'class' => FigGroup::class,
            ])
            ->add('images', FileType::class, [
                'label' => "Ajouter une ou des images",
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('videos', TextareaType::class, [
                'label' => "URL de la vidéo ( pour intégrer plusieurs vidéos, mettre une virgule sans espace entre chaque URL )",
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}
