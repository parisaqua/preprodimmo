<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Mailer;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class AccountController extends AbstractController
{
    /**
     * Pour se sonnecter
     * @Route("/login", name="account.login") 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();


        
        return $this->render('account/login.html.twig', [
            'hasError' => $error,
            'username' => $username,
            'translation_domain' => "security"
        ]);
    }


    /**
     * @Route("/inactif", name="inactive.account")
     *
     * @return void
     */
    public function inactiveUser() {
        return $this->render('account/inactive.html.twig');
    }

    /**
     * Pour se déconnecter
     * @Route("/logout", name="account.logout")
     * @return void
     */
    public function logout() {

    }

    /**
     * 
     * @Route("/bienvenue", name="account.bienvenue")
     * @return void
     */
    public function bienvenue() {
        return $this->render('confirmation/afterRegistrated.html.twig');
    }


    // /**
    //  * Undocumented function
    //  *
    //  * @return void
    //  */
    // public function afterRegistrated() {

    // }

    /**
     * Permet d'afficher le formulaire d'inscription
     * @Route("/register", name="account.register")
     * @return response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, Mailer $mailer, TokenGeneratorInterface $tokenGenerator){
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            // création du token
            $user->setToken($tokenGenerator->generateToken());
            // enregistrement de la date de création du token
            $user->setUserRegistratedAt(new \Datetime());
            
            $manager->persist($user);
            $manager->flush();

             // on utilise le service Mailer créé précédemment
             $bodyMail = $mailer->createBodyMail('confirmation/mail.html.twig', [
                'user' => $user
            ]);

            $mailer->sendMessage(
                'ne-pas-repondre@immobilier.digital', 
                $user->getEmail(), 
                'Immobilier Digital - Nouveau membre - confirmation d\'adresse e-mail. ', 
                $bodyMail
                );

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success',
                "Votre compte a bien été créé."
                );

            return $this->redirectToRoute("account.bienvenue");

            // $this->addFlash(
            //     'success',
            //     "Votre compte a bien été créé. Vous allez recevoir un e-mail afin d'activer votre compte."
            // );

            // return $this->redirectToRoute('account.login');
        }

        return $this->render('account/registration.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @Route("/account", name="account.show")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount() {
        return $this->render('account/show.html.twig', [
            'user' => $this->getUser()
        ]);

    }

    /**
     * Permet de modifier le formulaire de modification de profil
     * @Route("account/edition", name="account.profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager): Response {
        $user= $this->getUser();

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données ont été enregistrées avec succès !"
            );

            return $this->redirectToRoute('account.show');
        }

        return $this->render('account/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/password-update", name="account.password")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                // Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));
            }
            else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié"
                );

                return $this->redirectToRoute('account.show');
            }

            password_verify('password', '');
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }




}
