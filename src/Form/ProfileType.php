<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProfileType extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $gender = ['M.' => 'Monsieur', 'Mme.' => 'Madame'];

        $builder
            ->add('gender', ChoiceType::class, ['choices' => $this->getChoices(),'label' => 'Civilité'])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => true
                ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => true
                ])    
            ->add('description', TextareaType::class, [
                    'required' => false,
                    'label' => "Présentation",
                    'attr' => [
                        'placeholder' => 'Présentez-vous en quelques mots...'."\n" .'Seuls les membres d\'Immo Digital pourront voir votre prose.',
                        'rows' => 6, 
                        'cols' => 100
                    ]
                ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'label' => false,
                'allow_delete' => true,
                // 'download_label' => 'Téléchargement',
                'download_uri' => false,
                // 'image_uri' => false,
                // 'imagine_pattern' => 'avatar', //nom dans Liip_image
                'asset_helper' => true,
                'delete_label' => 'Supprimer l\'image actuelle ?',
                
                'attr' => [
                    'onchange'    => 'previewFile()',
                    'placeholder' => 'Une photo ?',
                ]
                
            ])
            
           
            ->add('telephoneM', TelType::class, [
                'required' => false,
                'label' => 'Téléphone portable'
                
            ])
            ->add('telephoneH', TelType::class, [
                'required' => false,
                'label' => 'Téléphone domicile'
                
            ])
            ->add('telephoneO', TelType::class, [
                'required' => false,
                'label' => 'Téléphone bureau'
                
            ])
            

            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }

    private function getChoices()
    {
        $choices = Profile::GENDER;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }

   
}
