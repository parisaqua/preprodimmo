<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Mailer;
use App\Form\ResettingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * @Route("/confirmation-inscription")
 */
class ConfirmationController extends AbstractController
{
    // si supérieur à 24 heures, retourne false
    // sinon retourne false
    private function isRequestInTime(\Datetime $userRegistratedAt = null)
    {
        if ($userRegistratedAt === null)
        {
            return false;        
        }
        
        $now = new \DateTime();
        $interval = $now->getTimestamp() - $userRegistratedAt->getTimestamp();

        $daySeconds = 60 * 60 * 24; // sec * min * heures
        $response = $interval > $daySeconds ? false : $reponse = true;
        return $response;
    }

   

    /**
     * 
     * @Route("/confirmation", name="account.confirmation")
     * 
     */
    public function activationConfirmed() {
        return $this->render('confirmation/confirmation.html.twig');
    }


    /**
     * @Route("/{id}/{token}", name="confirming")
     */
    public function confirming(User $user, $token, Request $request)
    {
        // interdit l'accès à la page si:
        // le token associé au membre est null
        // le token enregistré en base et le token présent dans l'url ne sont pas égaux
        // le token date de plus de 24 heures
        if ($user->getToken() === null || $token !== $user->getToken() || !$this->isRequestInTime($user->getUserRegistratedAt()))
        {
            throw new AccessDeniedHttpException();
        }

        // mettre en place la logique de pass isActive à true et donner le role de gestionnaire et propriétaire aux nouveaux connectés ...

        $user->setIsActive(true);
        $user->setRoles(['ROLE_PROPERTYMANAGER', 'ROLE_PROPERTYOWNER']);
        
        // réinitialisation du token et la date d'enregistrement à null pour qu'il ne soit plus réutilisable
        $user->setToken(null);
        // $user->setUserRegistratedAt(null);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', "Votre compte est maintenant actif.");

        return $this->redirectToRoute('account.confirmation');

        //return $this->render('resetting/index.html.twig');
        
    }


}