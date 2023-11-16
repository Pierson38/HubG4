<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Promo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseProfessorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'attr' => [
                "class" => "form-control mb-2"
            ],
            "label" => "Titre"
        ])
        ->add('startAt', null, [
            'attr' => [
                "class" => "form-control mb-2"
            ],
            "label" => "Commence le"
        ])
        ->add('endAt', null, [
            'attr' => [
                "class" => "form-control mb-2"
            ],
            "label" => "Fini le"
        ])
        ->add('classroom', TextType::class, [
            'attr' => [
                "class" => "form-control mb-2"
            ],
            "label" => "Salle de cours"
        ])
        ->add('tags', CollectionType::class, [
            'attr' => [
                "class" => "mb-2"
            ],
            "entry_type" => TagsType::class,
            "allow_add" => true,
            "allow_delete" => true,
            "by_reference" => false,
            "entry_options" => [
                "label" => false
            ]
        ])
        ->add('description', TextareaType::class, [
            'attr' => [
                "class" => "form-control mb-2"
            ],
            "required" => false
        ])
        ->add('promo', EntityType::class, [
            'attr' => [
                "class" => "form-control mb-2"
            ],
            "class" => Promo::class,
            "choice_label" => "name"
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courses::class,
        ]);
    }
}
