<?php

namespace App\Security;

use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class RedirectAfterLogin extends AccountStatusException
{
     /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Nous essayons de vous rediriger ...  '      
        ;
    }

    public function classAlert() {
        return 'success';
    }


  
}