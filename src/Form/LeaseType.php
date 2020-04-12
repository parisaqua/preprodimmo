<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Lease;
use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LeaseType extends AbstractType
{
    
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
              ->add('effectDate', TextType::class, [
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
                ])
            ->add('signatureDate', TextType::class, [
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
             ])
            ->add('length')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'habitation' => '1',
                    'bureau' => '2',
                    'commerce' => '3',
                ], 
                ])
            ->add('rent')
            ->add('charges')
            ->add('vat')
            ->add('paymentTerm')
            ->add('owner', EntityType::class, array(
                'class' => User::class,
                'label' => 'Bailleur',
                'required' => true,
                'choice_label' => 'fullName',     
            ))
            ->add('tenant', EntityType::class, array(
                'class' => User::class,
                'label' => 'Preneur',
                'required' => true,
                'choice_label' => 'fullName', 
                'multiple' => true,    
            ))
            ->add('property', EntityType::class, array(
                'class' => Property::class,
                'label' => 'Bien',
                'required' => true,
                'choice_label' => 'address',     
            ))
        ;

        $builder->get('effectDate')->addModelTransformer($this->transformer);
        $builder->get('signatureDate')->addModelTransformer($this->transformer);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lease::class,
        ]);
    }
}
