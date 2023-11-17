<?php

namespace App\Form;

use App\Entity\Promo;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'BDE' => 'ROLE_BDE',
                    'Intervenant' => 'ROLE_PROFESSOR',
                    'COP' => 'ROLE_COP',
                ],
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
                'label' => 'Nom',
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
            ])
            ->add('promo', EntityType::class, [
                'class' => Promo::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
