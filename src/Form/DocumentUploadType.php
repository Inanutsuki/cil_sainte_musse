<?php

namespace App\Form;

use App\Entity\DocumentUpload;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DocumentUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('documentName')
            ->add('documentFile', VichFileType::class, [
                'label' => 'Document (format PDF)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'ancien fichier ?',
                'download_uri' => true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DocumentUpload::class,
        ]);
    }
}
