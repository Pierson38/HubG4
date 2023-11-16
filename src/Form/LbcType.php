<?php

namespace App\Form;

use App\Entity\Lbc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                    'class' => 'form-control mb-2']
            ])
            ->add('description', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control mb-2']
            ])
            ->add('price', NumberType::class,[
                'attr' => [
                    'class' => 'form-control mb-2']
            ])

            ->add('category', ChoiceType::class, [
                'choices' =>[
                    'Informatique' => 'Informatique',
                    'Mobilier' => 'Mobilier',
                    'Transport'  => 'Transport',
                    'Appartement'  => 'Appartement',
                ],
                'attr' => [
                    'class' => 'form-control mb-2']
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
