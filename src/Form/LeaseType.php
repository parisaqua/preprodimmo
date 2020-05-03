<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Lease;
use App\Entity\Property;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use App\Repository\LeaseRepository;
use App\Repository\PropertyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LeaseType extends AbstractType
{
    
    private $transformer;
    private $propertyRepository;
    private $security;
   
    public function __construct(FrenchToDateTimeTransformer $transformer, PropertyRepository $propertyRepository, Security $security)
    {
        $this->transformer = $transformer;
        $this->propertyRepository = $propertyRepository;
        $this->security = $security;
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
            ->add('rent', MoneyType::class)
            ->add('charges', MoneyType::class)
            ->add('vat')
            ->add('paymentTerm', ChoiceType::class, [
                'choices'  => [
                    'mensuelle' => '12',
                    'trimestrielle' => '4',
                    'semestrielle' => '2',
                    'annuelle' => '1'
                ], 
                ])
            ->add('owner', EntityType::class, array(
                'class' => User::class,
                'label' => 'Bailleur',
                'required' => true,
                'choice_label' => 'fullName',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :role')
                        ->setParameter('role', '%'."ROLE_PROPERTYOWNER".'%')
                        ->orderBy('u.lastName', 'ASC');   
                },          
            ))
            ->add('tenant', EntityType::class, array(
                'class' => User::class,
                'label' => 'Preneur',
                'required' => true,
                'choice_label' => 'fullName', 
                'multiple' => true,  
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :role')
                        ->setParameter('role', '%'."ROLE_PROPERTYTENANT".'%')
                        ->orderBy('u.lastName', 'ASC');   
                },       
            ));

            $user = $this->security->getUser()->getId();
            dump($user);

            $builder
            ->add('property', EntityType::class, array(
                'class' => Property::class,
                'label' => 'Bien',
                'required' => true,
                'choice_label' => 'detailedProperty', 
                'choices' => $this->propertyRepository->findByManager($user),
                'empty_data' => 'John Doe',
            ))
        ;

        $builder->get('effectDate')->addModelTransformer($this->transformer);
        $builder->get('signatureDate')->addModelTransformer($this->transformer);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lease::class,
            'translation_domain' => "forms",
        ]);
    }
}
