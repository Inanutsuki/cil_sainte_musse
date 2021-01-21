<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Vous n'avez pas tapé les mêmes mots de passe",
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => "Votre mot de passe :"],
                'second_options' => ['label' => "Confirmer le mot de passe :"],
            ])
            ->add('adresse', TextType::class)
            ->add('zipCode', TextType::class)
            ->add('city', TextType::class)
            ->add('birthday', DateType::class, [
                'format' => 'dd MM yyyy',
                'years' =>range(1900,2021),
                'placeholder' => [
                    'day' => "Jour",
                    'month' => "Mois",
                    'year' => "Année"
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Ajouter une photo de profil (fichier JPG ou PNG)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image ?',
                'download_uri' => false,
                'imagine_pattern' => 'users_avatar',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
