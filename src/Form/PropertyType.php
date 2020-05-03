<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Option;
use App\Entity\Property;
use App\Form\DocumentType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PropertyType extends AbstractType
{
    private $userRepository;
    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    } 
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();
        
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price', MoneyType::class, [
                'label'=>'Estimation du prix',
                'required' => false,
            ])
            ->add('heat', ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Appartement' => 1,
                    'Maison' => 2,
                    'Bureau' => 3,
                    'Local commercial' => 4,
                    'Garage' => 5,
                ],
                'label' => 'Type',
                'placeholder' => 'Type de bien ...',
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                
            ])
            ->add('city')
            ->add('address')
            ->add('postalCode')
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
            // ->add('sold')
            // ->add('rented')
            ->add('landing', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Face, Gauche, ...'
                    ]
            ])
            ->add('access', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Code d\'accès, interphone, ...'
                    ]
             ])
            ->add('pictureFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])  
            ->add('documents', CollectionType::class, [
                'entry_type' => DocumentType::class,
                // 'entry_options' =>  ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
                'required' => true,
            ])    

            

            // ->add('owner', EntityType::class, array(
            //     'class' => User::class,
            //     'label' => 'Propiétaire',
            //     'multiple' => true,
            //     'required' => true,
            //     'choice_label' => 'fullId',
            //     'choices' => $this->userRepository->findOwnerAlphabetical(), 
            // ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => "forms"
        ]);
    }

    public function getChoices() {
        $choices = Property::HEAT;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
