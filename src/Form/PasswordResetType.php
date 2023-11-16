<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Entrez votre nouveau mot de passe',
                    'class' => 'form-control', // Add your styling here
                    'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$',
                    'title' => 'Doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial',
                    'minlength' => '8',
                ],
            ],
            'second_options' => [
                'label' => 'Repetez votre nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Repetez votre nouveau mot de passe',
                    'class' => 'form-control', // Add your styling here
                    'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$',
                    'title' => 'Doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial',
                    'minlength' => '8',
                ],
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
