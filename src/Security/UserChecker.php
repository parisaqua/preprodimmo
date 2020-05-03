<?php
namespace App\Security;

use App\Entity\User as AppUser;
use App\Security\RedirectAfterLogin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Security\AccountNotActivatedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class UserChecker implements UserCheckerInterface
{
    
    private  $entityManager;


    public function __construct( EntityManagerInterface $entityManager ) {
        $this->entityManager = $entityManager;
    }

    public function checkPreAuth(UserInterface $user)
    {
        
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->getIsActive(true)) {
            throw new AccountNotActivatedException();
        } 

       

        

    }
   
    public function checkPostAuth(UserInterface $user)
    {
        
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->getRoles() == '%'."ROLE_PROPERTYOWNER".'%') {
            throw new RedirectAfterLogin();
        } 

        // if ($user->hasCompleteProfile() == false) {
        //     $url = $this->router->generate('edit_profile');
    
        //     return new RedirectResponse($url);
        // }
    

        

    }
    
    
}