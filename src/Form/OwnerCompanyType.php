<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Company;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class OwnerCompanyType extends AbstractType
{
    private $userRepository;
    private $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();

        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('website', TextType::class, [
                'required' => false,
            ])
            ->add('telephone', TelType::class, [
                'required' => false,
            ]) 
            // ->add('member', EntityType::class, array(
            //     'class' => User::class,
            //     'label' => 'Collaborateur(s)',
            //     'multiple' => true,
            //     'required' => false,
            //     // 'required' => false,
            //     'choice_label' => 'fullName', 
            //     'choices' => $this->userRepository->findByCreator($user), 
            // ))

        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'translation_domain' => "forms"
        ]);
    }

   
}
