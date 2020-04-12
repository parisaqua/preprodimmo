<?php

namespace App\Controller\PropertyManager;

use App\Entity\Lease;
use App\Form\LeaseType;
use App\Repository\LeaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gestion/baux")
 */
class LeaseManagerController extends AbstractController
{
    /**
     * @Route("/", name="lease.manager.index", methods={"GET"})
     */
    public function index(LeaseRepository $leaseRepository): Response
    {
        return $this->render('manager/lease/index.html.twig', [
            'leases' => $leaseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lease.manager.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lease = new Lease();
        $form = $this->createForm(LeaseType::class, $lease);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lease);
            $entityManager->flush();

            return $this->redirectToRoute('lease.manager.index');
        }

        return $this->render('manager/lease/new.html.twig', [
            'lease' => $lease,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lease.manager.show", methods={"GET"})
     */
    public function show(Lease $lease): Response
    {
        return $this->render('manager/lease/show.html.twig', [
            'lease' => $lease,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lease.manager.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lease $lease): Response
    {
        $form = $this->createForm(LeaseType::class, $lease);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lease.manager.index');
        }

        return $this->render('manager/lease/edit.html.twig', [
            'lease' => $lease,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lease.manager.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Lease $lease): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lease->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lease);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lease.manager.index');
    }
}
