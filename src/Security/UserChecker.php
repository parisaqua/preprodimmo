<?php
namespace App\Security;

use App\Entity\User as AppUser;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\AccountDisabledException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

class UserChecker implements UserCheckerInterface
{
    
    private  $entityManager;


    public function __construct( EntityManagerInterface $entityManager ) {
        $this->entityManager = $entityManager;
    }

    public function checkPreAuth(UserInterface $user)
    {
        
        if (!$user->getIsActive(true)) {
            console.log('user inactif, Fred !');
         }
        
        if (!$user instanceof AppUser) {
            return;
        }

    }
   
    public function checkPostAuth(UserInterface $user)
    {
        
        if (!$user->getIsActive(true)) {
            console.log('user inactif, Fred !');
        } 

        if (!$user instanceof AppUser) {
            return;
        }

        
        // user account is not valided
        // if (!$user->getIsActive(true)) {

        //     // throw new AccountNotActivatedException("Votre compte n'est pas activé.");  
        //     throw new \Exception('Pas bon !');
        //  }

        

        // user account is expired, the user may be notified
        // if ($user->isExpired()) {
        //     throw new AccountExpiredException('...');
        // }

    }
    
    
}