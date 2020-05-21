<?php

namespace App\Controller\PropertyOwner;

use App\Entity\Company;
use App\Form\OwnerCompanyType;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * 
 */
class OwnerCompanyController extends AbstractController
{
    /**
     * @Route("/owner/company", name="company.owner.index", methods={"GET"})
     */
    public function index(CompanyRepository $companyRepository): Response
    {
        $user = $this->getUser()->getId();

        return $this->render('owner/company/index.html.twig', [
            'companys' => $companyRepository->findByCreator($user),
            'menu' => 'owner-company'
        ]);
    }

    /**
     * @Route("/owner/company/new", name="company.owner.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $company = new Company();

        $creator = $this->getUser()->getId();

        $form = $this->createForm(OwnerCompanyType::class, $company);
        $form->handleRequest($request);

        //$company->setMember();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            $company->setCreator($creator);
            
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company.owner.index');
            
            //revenir vers l'edition du user

            // return $this->redirectToRoute('owner.user.edit', [
            //     'id' => $id,
            // ]);
           
        }

        return $this->render('owner/company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/owner/company/{id}", name="company.owner.show", methods={"GET"})
     */
    public function show(Company $company): Response
    {
        return $this->render('owner/company/show.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @Route("/owner/company/{id}/edit", name="company.owner.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(OwnerCompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company.owner.index');
        }

        return $this->render('owner/company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/owner/company/{id}", name="company.owner.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Company $company): Response
    {   
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($company);
            $entityManager->flush();
        }
        return $this->redirectToRoute('company.owner.index');
    }

}
