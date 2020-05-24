<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder
            ->add('profile', ProfileRegistrationType::class)
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Propriétaire' => 'ROLE_PROPERTYOWNER',
                    'Locataire' => 'ROLE_PROPERTYTENANT',
                    'Gestionnaire' => 'ROLE_PROPERTYMANAGER',
                    'Responsable' => 'ROLE_MANAGER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded'  => false, // liste déroulante
                'multiple'  => true, // choix multiple
                'required'   => true,
                'label' => 'Rôle(s)',
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Actif ?',
                'required'   => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => "forms"
        ]);
    }

  
}
