<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nommer cette adresse',
                ],
                'required' => true,
            ])
            ->add('firstLine', TextType::class, [
                'attr' => [
                    'placeholder' => 'Numéro et voie ...',
                ]
            ])
            ->add('secondLine', TextType::class, [
                'attr' => [
                    'placeholder' => 'Complément d\'adresse'
                ]
            ])
            ->add('postalCode', TextType::class, [
                'attr' => [
                    'placeholder' => 'Code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('lat', HiddenType::class, [
                'attr' => [
                    'class' => 'lat'
                ]
            ])
            ->add('lng', HiddenType::class, [
                'attr' => [
                    'class' => 'lng'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
            'translation_domain' => "forms"
        ]);
    }
}
