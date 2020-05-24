<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'help' => 'Choisissez si vous le souhaitez un nom désignant cette adresse.',
                'empty_data' => 'mon adresse',
                'attr' => [
                    'placeholder' => 'Mon adresse'
                ]
            ])
            ->add('firstLine')
            ->add('secondLine')
            ->add('postalCode')
            ->add('city')
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'translation_domain' => "forms"
        ]);
    }
}
