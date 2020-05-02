<?php

namespace App\Controller\PropertyManager;

use App\Entity\Option;
use App\Entity\Document;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Form\AdminPropertyType;
use Doctrine\ORM\EntityManager;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyManagerController extends AbstractController {

    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param PropertyRepository $repository
     */
    public function __construct(PropertyRepository $repository, EntityManagerInterface $em) {

        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * Undocumented function
     *
     * @Route("admin/gestion/biens", name="property.manager.index")
     * 
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $properties = $paginator->paginate(

            $this->repository->findAllQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('manager/property/index.html.twig', [
            'current_menu' => 'manager',
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/portefeuille/mesbiens", name="myproperty.manager.index")
     *
     * @param PaginatorInterface $paginator
     * @param Request            $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myindex(PaginatorInterface $paginator, Request $request, UserInterface $user)
    {
        $properties = $paginator->paginate(

            $this->repository->findAllMyQuery($user),
            $request->query->getInt('page', 1), 
            10
        );

        return $this->render('manager/property/myIndex.html.twig', [
            'properties' => $properties,
            'current_menu' => 'mymanager',
        ]);
    }


    /**
     * Nouveau bien
     *
     * @Route("/gestion/biens/nouveau", name="property.manager.new")
     * 
     */
    public function new(Request $request, EntityManagerInterface $manager) {

        $property= new Property();

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            foreach($property->getDocuments() as $document) {
                $document->setProperty($property);
                $manager->persist($document);
            }
 
            $property->setAuthor($this->getUser());
            $property->setManager($this->getUser());

            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien créé avec succès !');

            return $this->redirectToRoute('myproperty.manager.index');
        }

        return $this->render('manager/property/new.html.twig', [
            'property' => $property,
            'current_menu' => 'admin',
            'form' => $form->createView()
        ]);
    }


    /**
     * Edition d'un bien
     *
     * @Route("portefeuille/gestion/biens/{id}", name="property.manager.edit", methods="POST|GET")
     * 
     * @Security("property.isManager(user)")
     * 
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     */
    public function edit(Property $property, Request $request, EntityManagerInterface $manager): Response {
        
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        $slug = $property->getSlug();

        if($form->isSubmitted() && $form->isValid()) {

            foreach($property->getDocuments() as $document) {
                $document->setProperty($property);
                $manager->persist($document);
            }
            
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');

            if($property->getSlug() !== $slug) {

                return $this->redirectToRoute('manager.property.show', [
                    'id' => $property->getId(),
                    'slug' => $property->getSlug()
                ], 301);
            }
    
            return $this->render('manager/property/show.html.twig', [
                'property' => $property,
                'current_menu' => 'properties',
            ]);


           // return $this->redirectToRoute('myproperty.manager.index'); 
        }

        return $this->render('manager/property/edit.html.twig', [
            'property' => $property,
            'current_menu' => 'manager',
            'form' => $form->createView(),
           
        ]);
    }

    /**
     * Edition d'un bien par Admin
     *
     * @Route("/admin/gestion/biens/{id}", name="admin.property.manager.edit", methods="POST|GET")
     * 
     * @return Response
     */
    public function adminEdit(Property $property, Request $request, EntityManagerInterface $manager): Response {
        
        $form = $this->createForm(AdminPropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
   
            foreach($property->getDocuments() as $document) {
                $document->setProperty($property);
                $manager->persist($document);
            }
            
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');

            return $this->redirectToRoute('property.manager.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'current_menu' => 'admin',
            'form' => $form->createView()
        ]);
    }


    /**
     * Supprimer un bien
     * 
     * @Route("/admin/biens/{id}", name="property.manager.delete", methods="DELETE")
     * 
     */

    public function delete(Property $property, Request $request) {

        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('property.manager.index');
    }

     /**
     * Détail d'un bien
     *
     * @Route("portefeuille/biens/{slug}-{id}", name="manager.property.show", requirements={"slug"= "[a-z0-9\-]*" })
     * 
     * @return Response
     */

    public function show(Property $property, string $slug, Request $request): Response {

        if($property->getSlug() !== $slug) {

            return $this->redirectToRoute('manager.property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }

        return $this->render('manager/property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
        ]);
    }

}