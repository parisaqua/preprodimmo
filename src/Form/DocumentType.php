<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('name', TextType::class, [ 
                'required' => true,
            ])
            ->add('kind', ChoiceType::class, [
                'choices'  => [
                    'commercial' => '1',
                    'gestion' => '2',
                    'autre' => '3',
                ],
                'placeholder' => 'Choisir un type de document',
                'required' => true,
            ])

            ->add('documentFile', VichFileType::class, [
                'required' => true,
                'label' => false,
                'allow_delete' => false,
                'download_label' => 'Téléchargement',
                'download_uri' => false,
                'asset_helper' => true,
                'delete_label' => 'Supprimer l\'image actuelle ?',
            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}


