<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (fichier JPG ou PNG)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image ?',
                'download_uri' => false,
                'imagine_pattern' => 'edit_article_view_thumbnail',
            ]);
        if (in_array('ROLE_ADMIN', $options['roles'])) {

            $builder->add('isValided', CheckboxType::class, [
                'label'    => "Valider l'article",
                'required' => false,
            ]);
        };
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'roles' => null,
        ]);
    }
}
