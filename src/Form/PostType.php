<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, [
                'label' => 'Titre'
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenue'
            ])
            ->add('category', EntityType::class, [
                'label' => 'Choisir une catégorie',
                'class' => Category::class,
                'choice_label' => 'title' 
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (fichier JPG ou PNG)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image ?',
                'download_uri' => false,
                'imagine_pattern' => 'edit_article_view_thumbnail',
            ]);
        if (in_array('ROLE_ADMIN', $options['roles'])) {

            $builder
            ->add('isValided', CheckboxType::class, [
                'label'    => "Valider l'article",
                'required' => false,
            ])
            ->add('forMembers', CheckboxType::class, [
                'label'    => "Visible seulement par les membres",
                'required' => false,
            ])
            ->add('forAssembly', CheckboxType::class, [
                'label'    => "Visible seulement par les membres de l'assemblée",
                'required' => false,
            ])
            ->add('forIndex', CheckboxType::class, [
                'label'    => "Afficher sur la page d'accueil",
                'required' => false,
            ]);
        };
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'roles' => [],
        ]);
    }
}
