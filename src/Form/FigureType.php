<?php

namespace App\Form;

use App\Entity\Figure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            /* ->add('figGroup', TextType::class, [
                'label' => "Groupe",
                'attr' => [ 
                    'placeholder'=> "Nom du groupe" 
                ]]) */ ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
} 
