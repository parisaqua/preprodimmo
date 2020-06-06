<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Profile;
use App\Form\AddressType;
use App\Form\ProfileType;
use App\Form\LocationType;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OwnerProfileType extends AbstractType
{
    
    // private $security;
    // private $companyRepository;

    // public function __construct(Security $security, CompanyRepository $companyRepository)
    // {
    //     $this->security = $security;
    //     $this->companyRepository = $companyRepository;
    // }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gender = ['M.' => 'Monsieur', 'Mme.' => 'Madame'];

        // $user = $this->security->getUser();
        
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
            // ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => "Présentation",
                'attr' => [
                    'placeholder' => 'Présentez-vous en quelques mots...'."\n" .'Les visiteurs du site pourront lire votre prose.',
                    'rows' => 5, 
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
            ->add('locations', CollectionType::class, [
                'label' => 'Mes adresses',
                'entry_type' => LocationType::class,
                // 'entry_options' =>  ['label' => false],
                'prototype' => true,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'required' => true,
            ])

            ->add('companyRelated', CheckboxType::class, [

            ])

            ->add('company', EntityType::class, array(
                'class' => Company::class,
                'label' => false,
                'required' => true,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'societe'
                ]
                // 'query_builder' => function (EntityRepository $er) {
                //     return $er->createQueryBuilder('u')
                //         ->andWhere('u.roles LIKE :role')
                //         ->setParameter('role', '%'."ROLE_PROPERTYMANAGER".'%')
                //         ->orderBy('u.lastName', 'ASC');   
                // },     
            ))

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
            'translation_domain' => "forms"
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
