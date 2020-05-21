<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Company;
use App\Form\OwnerCompanyType;
use App\Repository\CompanyRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OwnerAccountType extends AbstractType
{
    
    private $security;
    private $companyRepository;

    public function __construct(Security $security, CompanyRepository $companyRepository)
    {
        $this->security = $security;
        $this->companyRepository = $companyRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gender = ['M.' => 'Monsieur', 'Mme.' => 'Madame'];

        $user = $this->security->getUser();
        
        $builder
            ->add('gender', ChoiceType::class, ['choices' => $this->getChoices(),'label' => 'Civilité'])
            ->add('firstName', TextType::class, ['label' => 'First Name'])
            ->add('lastName', TextType::class, ['label' => 'Last Name'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Locataire' => 'ROLE_PROPERTYTENANT',
                    'Prestataire' => 'ROLE_PROPERTYSUPPLIER',
                    'Tiers' => 'ROLE_PROPERTYOTHERS',
                ],
                'expanded'  => false, // liste déroulante
                'multiple'  => true, // choix multiple
                'required'   => true,
                'label' => 'Rôle(s)',
                'attr' => [
                    'lang' => 'fr'
                    ]
            ])

            ->add('city', TextType::class, [
                'required' => false,
                
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'id' => 'address_immo'
                ],
               
               
            ])
            ->add('postalCode', TextType::class, [
                'required' => false,
            ])
            ->add('telephone', TelType::class, [
                'required' => false,
            ])

            ->add('companyRelated')

            ->add('company', EntityType::class, array(
                'class' => Company::class,
                'label' => 'Société',
                // 'required' => false,
                'choice_label' => 'name',  
                'choices' => $this->companyRepository->findByCreator($user), 
            ))

            
            
        ;

        
        // $builder->add('company', OwnerCompanyType::class, [
        //     'label' => false,
        // ]);

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
