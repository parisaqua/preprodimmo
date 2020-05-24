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

class ProfileRegistrationType extends  AbstractType
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
