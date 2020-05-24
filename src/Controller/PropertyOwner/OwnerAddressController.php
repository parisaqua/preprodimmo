<?php

namespace App\Controller\PropertyOwner;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/address")
 */
class OwnerAddressController extends AbstractController
{

    /**
     * @Route("/", name="address_index", methods={"GET"})
     * 
     */
    public function index(AddressRepository $addressRepository): Response
    {
        $user = $this->getUser()->getId();
        
        return $this->render('address/index.html.twig', [
            'addresses' => $addressRepository->findByCreator($user),
            'menu' => 'owner-address'
        ]);
    }

    /**
     * @Route("/new", name="address_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $address = new Address();
        $creator = $this->getUser()->getId();

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $address->setCreator($creator);
            $name = $address->getName();
            $address->setName($name.'-'.$creator);

            $entityManager->persist($address);
            $entityManager->flush();

            // return $this->redirectToRoute('address_index');
            return $this->redirectToRoute('address_show', [
                'id' => $address->getId(),
            ], 301);
        }

        return $this->render('address/new.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_show", methods={"GET"})
     * @Security("user.getId() == address.getCreator()")
     */
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="address_edit", methods={"GET","POST"})
     * @Security("user.getId() == address.getCreator()")
     * 
     */
    public function edit(Request $request, Address $address): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $creator = $address->getCreator();
            $name = $address->getName();
            $address->setName($name.'-'.$creator);

            $this->getDoctrine()->getManager()->flush();

            // return $this->redirectToRoute('address_index');
            return $this->redirectToRoute('address_show', [
                'id' => $address->getId(),
            ], 301);
        }

        return $this->render('address/edit.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Address $address): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('address_index');
        
    }
}
