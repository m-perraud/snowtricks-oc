<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\FigGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
                    'placeholder'=> "Titre de l'article"
                ]])
            ->add('content', TextareaType::class, [
                'label' => "Contenu",
                'attr' => [ 
                    'placeholder'=> "Contenu de l'article" 
                ]])
            ->add('figGroup', EntityType::class, [
                'class' => FigGroup::class,
            ])
            ->add('images', FileType::class,[
                        'label' => false,
                        'multiple' => true,
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Please add a file',
                            ])],
                    ])
            ->add('videos', TextareaType::class,[
                        'label' => "URL de la vidéo",
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Please add an URL',
                            ])],
                    ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
} 
