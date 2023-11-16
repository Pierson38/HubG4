<?php

namespace App\Form;

use App\Entity\Events;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description (optional)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'label' => 'Lieu',
                'attr' => ['class' => 'form-control']
            ])
            ->add('startAt', null, [
                'label' => 'Date de début',
                'widget' => 'choice',
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'max-height: 100px; overflow-y: auto; border: none;'
                ],
            ])
            ->add('endAt', null, [
                'label' => 'Date de fin',
                'widget' => 'choice',
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'max-height: 100px; overflow-y: auto; border: none;'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
