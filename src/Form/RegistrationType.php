<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ProfileRegistrationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends AbstractType
{
    

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gender = ['M.' => 'Monsieur', 'Mme.' => 'Madame'];

        $builder

            ->add('profile', ProfileRegistrationType::class)

            ->add('email', EmailType:: class, [
                'attr' => [
                    'placeholder' => 'Votre adresse e-mail ...'
                    ]
            ])
            ->add('hash', PasswordType:: class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Choisir un mot de passe'
                    ]
            ])
            ->add('passwordConfirm', PasswordType:: class, [
                'label' => 'Confirmation',
                'attr' => [
                    'placeholder' => 'Comfimer le mot de passe'
                    ]
            ])
            ->add('termsAccepted', CheckboxType::class, array(
                'mapped' => false,
                'constraints' => new IsTrue(),
                'label' => 'J\'accepte les conditons générales' ,
                
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => "forms"
        ]);
    }

    private function getChoices()
    {
        $choices = User::GENDER;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
