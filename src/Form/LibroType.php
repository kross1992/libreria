<?php

namespace App\Form;

use App\Entity\Libro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', TextType::class, array(
                'label'    => 'Titulo:',
                'required' => true,
            ))
            ->add('descripcion', TextAreaType::class, array(
                'label'    => 'Descripción:',
                'required' => true,
            ))
            ->add('idioma', TextType::class, array(
                'label'    => 'Fecha Publicación:',
                'required' => true,
            ))
            ->add('autor', TextType::class, array(
                'label'    => 'Autor:',
                'required' => true,
            ))
            ->add('cantidad_paginas', IntegerType::class, array(
                'label'    => 'Cantidad Paginas:',
                'required' => true,
            ))
            ->add('save', SubmitType::class,array(
                'attr' => array('class' =>'btn btn-success waves-effect waves-light btn-block','style' => 'display:inline-block'),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Libro::class,
        ]);
    }
}
