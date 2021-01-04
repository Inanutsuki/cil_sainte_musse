<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('adresse')
            ->add('zipCode')
            ->add('city')
            ->add('birthday', DateType::class, [
                'format' => 'dd MM yyyy',
                'years' => range(1900, 2021),
                'placeholder' => [
                    'day' => "Jour",
                    'month' => "Mois",
                    'year' => "Année"
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (fichier JPG ou PNG)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image ?',
                'download_uri' => false,
                'imagine_pattern' => 'users_avatar',
            ]);
            dd('test');
        if (in_array('ROLE_ADMIN', $options['roles'])) {

            $builder->add('roles', CollectionType::class, [
                'entry_type'   => ChoiceType::class,
                'entry_options'  => [
                    'label' => false,
                    'choices' => User::EDITABLE_ROLES,
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'roles' => null,
        ]);
    }
}
