<?php

namespace App\Form;

use App\Entity\Carpool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarpoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('fromLocation', TextType::class, [
                'label' => 'De',
                'attr' => [
                    'placeholder' => 'Adresse de départ',
                    'class' => 'form-control mb-4',
                    "autocomplete" => "from address-line1",
                    "data-type" => "from"
                ]
            ])
            ->add('toLocation', TextType::class, [
                'label' => 'À',
                'attr' => [
                    'placeholder' => "Adresse d'arrivée",
                    'class' => 'form-control mb-4',
                    "autocomplete" => "to address-line1",
                    "data-type" => "to"
                ]
            ])
            ->add('places', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => [
                    'placeholder' => 'Nombre de places',
                    'class' => 'form-control mb-4'
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
                'attr' => [
                    'placeholder' => 'Prix',
                    'class' => 'form-control mb-4'
                ]
            ])
            ->add('fromLat', HiddenType::class)
            ->add('fromLong', HiddenType::class)
            ->add('toLat', HiddenType::class)
            ->add('toLong', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carpool::class,
        ]);
    }
}
