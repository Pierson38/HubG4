<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ],
            'first_options' => [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'class' => 'form-control mb-3', // Add your desired classes here
                    'placeholder' => 'Nouveau mot de passe',
                    'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$',
                    'title' => 'Doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial',
                    'minlength' => '8',
                ],
            ],
            'second_options' => [
                'label' => 'Répéter le mot de passe',
                'attr' => [
                    'class' => 'form-control mb-3', // Add your desired classes here
                    'placeholder' => 'Re-tapez nouveau mot de passe',
                    'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$',
                    'title' => 'Doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial',
                    'minlength' => '8',
                ],
            ],
            'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
