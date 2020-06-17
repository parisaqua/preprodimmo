<?php

namespace App\Controller\PropertyOwner;

use Faker\Factory;
use App\Entity\Profile;
use App\Entity\Location;
use App\Form\AccountType;
use App\Form\ProfileType;
use App\Entity\PasswordUpdate;
use App\Form\OwnerProfileType;
use App\Form\PasswordUpdateType;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class OwnerProfileController extends AbstractController
{  
    /**
     * Permet d'afficher la liste des contacts
     * @Route("/owner/contact", name="owner.contact.index", methods={"GET"})
     * @Security("is_granted('ROLE_PROPERTYOWNER')", message="Vous n'avez pas les droits pour accèder à cette page.")
     */
    public function index(ProfileRepository $profileRepository): Response
    {
        $user = $this->getUser()->getId();
        
        return $this->render('owner/contact/index.html.twig', [
            'profiles' => $profileRepository->findByCreator($user),
            'menu' => 'owner-user'
        ]);
    }

   

    /**
     * Permet d'ajouter un contact
     * @Route("/owner/contact/new", name="owner.contact.new")
     * @return response
     */
    public function addContact(Request $request, EntityManagerInterface $manager){
        
        $profile = new Profile();
        $creator = $this->getUser()->getId();
  
        $form = $this->createForm(OwnerProfileType::class, $profile);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){

            if($profile->getCompanyRelated() == false) {
                $profile->setCompany(null);
            }
            
            foreach($profile->getLocations() as $location) {
                $location->setProfile($profile);
                $location->setCreator($creator);
                $manager->persist($location);
            }
            
            $profile->setCreator($creator);

            $manager->persist($profile);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau contact a bien été créé."
            );

            return $this->redirectToRoute('owner.contact.index');
        }

        return $this->render('owner/contact/new.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    

    /**
     * Permet de modifier un contact
     * @Route("owner/contact/{id}/edition", name="owner.contact.edit", methods={"GET","POST"})
     * 
     */
    public function edit(Request $request, Profile $profile, EntityManagerInterface $manager): Response
    {
        $creator = $this->getUser()->getId();
        
        $form = $this->createForm(OwnerProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($profile->getCompanyRelated() == false) {
                $profile->setCompany(null);
            }
            
            foreach($profile->getLocations() as $location) {
                $location->setProfile($profile);
                $location->setCreator($creator);
                $manager->persist($location);
            }
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                "Le contact a été mis à jour avec succès !"
            );

            return $this->redirectToRoute('owner.contact.index');
        }

        return $this->render('owner/contact/edit.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
        ]);
    }
    

    
    /**
    * @Route("/owner/contact/{id}", name="owner.contact.delete", methods={"DELETE"})
    * 
    */
    public function delete(Request $request, Profile $profile): Response
    {   
        if ($this->isCsrfTokenValid('delete'.$profile->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($profile);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le contact a bien été supprimé !"
            );
        }
        return $this->redirectToRoute('owner.contact.index');
    }



}
