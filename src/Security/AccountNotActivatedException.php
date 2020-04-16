<?php

namespace App\Security;

use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class AccountNotActivatedException extends AccountStatusException
{
     /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Vous êtes bien inscrit sur Immobilier.digital, 
        mais votre compte n\'a pas encore été activé ! 
        Cliquez sur le lien ci-dessous pour recevoir à nouveau le message d\'activation'      
        ;
    }

    public function classAlert() {
        return 'warning';
    }

  
}