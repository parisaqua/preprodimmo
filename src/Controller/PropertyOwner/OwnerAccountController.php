<?php

namespace App\Controller\PropertyOwner;

use Faker\Factory;
use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\OwnerAccountType;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class OwnerAccountController extends AbstractController
{  
    /**
     * Permet d'afficher la liste des users
     * @Route("/owner/users", name="owner.user.index", methods={"GET"})
     * @Security("is_granted('ROLE_PROPERTYOWNER')", message="Vous n'avez pas les droits pour accèder à cette page.")
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $user = $this->getUser()->getId();
        
        return $this->render('owner/user/index.html.twig', [
            'users' => $userRepository->findByCreator($user),
            'menu' => 'owner-user'
        ]);
    }

    /**
     * Permet d'ajouter un user
     * @Route("/owner/user/new", name="owner.user.new")
     * @return response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        
        $user = new User();

        $creator = $this->getUser()->getId();
        dump($creator);

        $faker = Factory::create('fr_FR');

        $form = $this->createForm(OwnerAccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $hash =  $encoder->encodePassword($user, $faker->password);
            $user->setHash($hash);

            $user->setCreator($creator);
            $user->setIsActive(true);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau contact a bien été créé."
            );

            return $this->redirectToRoute('owner.user.index');
        }

        return $this->render('owner/user/new.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * Permet de modifier un user
     * @Route("owner/user/{id}/edition", name="owner.user.edit", methods={"GET","POST"})
     * @return Response
     */
    public function profile(Request $request, User $user, EntityManagerInterface $manager): Response {
        
        $form = $this->createForm(OwnerAccountType::class, $user);
        $form->handleRequest($request);
        $companyRelated = $user->getCompanyRelated();

        dump($companyRelated);
        
        if($form->isSubmitted() && $form->isValid()){

            if($companyRelated === false) {
                $user->setCompany(Null);
            }
            
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le contact a été mis à jour avec succès !"
            );

            return $this->redirectToRoute('owner.user.index');
        }

        return $this->render('owner/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            
        ]);
    }

    
    /**
    * @Route("/owner/user/{id}", name="owner.user.delete", methods={"DELETE"})
    */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le contact a bien été supprimé !"
            );
        }

        return $this->redirectToRoute('owner.user.index');
    }



}
