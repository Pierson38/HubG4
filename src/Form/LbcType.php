<?php

namespace App\Form;

use App\Entity\Lbc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LbcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
                "label" => "Titre"
            ])
            ->add('description', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
                "label" => "Description"
            ])
            ->add('price', IntegerType::class,[
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
                "label" => "Prix"
            ])
            ->add('category', ChoiceType::class, [
                'choices' =>[
                    'Informatique' => 'Informatique',
                    'Mobilier' => 'Mobilier',
                    'Transport'  => 'Transport',
                    'Appartement'  => 'Appartement',
                ],
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
                "label" => "Catégorie"
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control mb-2',
                    'accept' => 'image/*',
                ],
                "label" => "Images"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lbc::class,
        ]);
    }
}
